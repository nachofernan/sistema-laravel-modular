<?php

namespace App\Helpers;

use App\Services\EmailDispatcher;
use Carbon\Carbon;

class EmailHelper
{
    /**
     * Enviar correos masivos INMEDIATOS sin tracking
     * Ideal para: tickets, notificaciones generales, confirmaciones
     * 
     * @param array $destinatarios Lista de correos
     * @param mixed $mailable Instancia del Mailable
     * @param string $tipo Tipo de correo (ticket, notificacion, etc)
     * @param string $descripcion Descripción para logs
     * @return int Cantidad de correos programados
     */
    public static function enviarMasivo(array $destinatarios, $mailable, $tipo = 'general', $descripcion = '')
    {
        return EmailDispatcher::enviarMasivo($destinatarios, $mailable, $tipo, $descripcion);
    }

    /**
     * Enviar correo prioritario (inmediato, cola priority)
     * Ideal para: reset password, códigos de verificación
     * 
     * @param string $destinatario
     * @param mixed $mailable
     * @param string $tipo
     * @param string $descripcion
     * @return void
     */
    public static function enviarPrioritario($destinatario, $mailable, $tipo = 'prioritario', $descripcion = '')
    {
        return EmailDispatcher::enviarPrioritario($destinatario, $mailable, $tipo, $descripcion);
    }

    /**
     * Enviar correos programados CON TRACKING (pueden ser cancelados)
     * Ideal para: concursos, recordatorios programados, notificaciones futuras
     * 
     * @param array $destinatarios
     * @param mixed $mailable
     * @param string $entityType Tipo de entidad (concurso, evento, etc)
     * @param int $entityId ID de la entidad
     * @param string $jobType Tipo específico del job (recordatorio_48hs, notificacion_cierre, etc)
     * @param Carbon|null $fechaEjecucion Cuándo ejecutar (null = inmediato)
     * @param string $descripcion
     * @param array $tags Tags adicionales
     * @return array Jobs creados
     */
    public static function programarConTracking(
        array $destinatarios, 
        $mailable, 
        $entityType, 
        $entityId, 
        $jobType,
        $fechaEjecucion = null,
        $descripcion = '',
        array $tags = []
    ) {
        return EmailDispatcher::enviarMasivoConTracking(
            $destinatarios,
            $mailable,
            $entityType,
            $entityId,
            $jobType,
            'programado', // tipo fijo para programados
            $descripcion,
            $fechaEjecucion,
            $tags
        );
    }

    // ==============================================
    // MÉTODOS ESPECÍFICOS PARA CASOS COMUNES
    // ==============================================

    /**
     * Para tickets (inmediato, sin tracking)
     */
    public static function enviarTicketNuevo($ticket, array $destinatarios)
    {
        $mailable = new \App\Mail\Tickets\Notificacion\TicketNuevo($ticket);
        
        return self::enviarMasivo(
            $destinatarios, 
            $mailable, 
            'ticket_nuevo', 
            "Ticket nuevo #{$ticket->id}"
        );
    }

    /**
     * Para notificaciones generales (inmediato, sin tracking)
     */
    public static function enviarNotificacion(array $destinatarios, $mailable, $descripcion = '')
    {
        return self::enviarMasivo($destinatarios, $mailable, 'notificacion', $descripcion);
    }

    // ==============================================
    // MÉTODOS ESPECÍFICOS PARA CONCURSOS
    // ==============================================

    /**
     * Notificar apertura de concurso (inmediato, con tracking por si se cancela)
     */
    public static function notificarAperturaConcurso($concurso, array $destinatarios)
    {
        $mailable = new \App\Mail\Concursos\ConcursoAbierto($concurso);
        
        return self::programarConTracking(
            $destinatarios,
            $mailable,
            'concurso',
            $concurso->id,
            'notificacion_apertura',
            null, // inmediato
            "Apertura - Concurso #{$concurso->numero}",
            ['concurso', 'apertura']
        );
    }

    /**
     * Notificar apertura de concurso (inmediato, con tracking por si se cancela)
     */
    public static function notificarConcursoAnulado($concurso, array $destinatarios)
    {
        $mailable = new \App\Mail\Concursos\ConcursoAnulado($concurso);
        
        return self::programarConTracking(
            $destinatarios,
            $mailable,
            'concurso',
            $concurso->id,
            'notificacion_concurso_anulado',
            null, // inmediato
            "Concurso anulado - Concurso #{$concurso->numero}",
            ['concurso', 'anulado']
        );
    }

    /**
     * Notificar apertura de concurso (inmediato, con tracking por si se cancela)
     */
    public static function notificarNuevoDocumentoConcurso($documento, array $destinatarios)
    {
        $mailable = new \App\Mail\Concursos\NuevoDocumento($documento);
        
        return self::programarConTracking(
            $destinatarios,
            $mailable,
            'concurso',
            $documento->concurso_id,
            'notificacion_nuevo_documento',
            null, // inmediato
            "Nuevo documento - Concurso #{$documento->concurso->numero}",
            ['concurso', 'nuevo_documento']
        );
    }

    /**
     * Programar recordatorio 48hs antes del cierre
     */
    public static function programarRecordatorio48hs($concurso, array $destinatarios)
    {
        if (!$concurso->fecha_cierre) return [];

        $fecha48HsAntes = Carbon::parse($concurso->fecha_cierre)->subHours(48);
        
        // Solo programar si es en el futuro
        if (!$fecha48HsAntes->isFuture()) return [];

        $mailable = new \App\Mail\Concursos\ProximoCierre($concurso);
        
        return self::programarConTracking(
            $destinatarios,
            $mailable,
            'concurso',
            $concurso->id,
            'recordatorio_48hs',
            $fecha48HsAntes,
            "Recordatorio 48hs - Concurso #{$concurso->numero}",
            ['concurso', 'recordatorio', 'automatico']
        );
    }

    /**
     * Enviar notificación de prorroga
     */
    public static function reprogramarEmailsProrroga($prorroga, array $destinatarios)
    {
        // 1. CANCELAR TODO LO ANTERIOR (Limpiar la mesa)
        // Esto es vital para que la tabla 'jobs' quede libre de este concurso 
        // y el Dispatcher no mande el mail de prórroga al final del tiempo.
        EmailDispatcher::cancelarJobsPorEntidad('concurso', $prorroga->concurso->id);

        // 2. ENVIAR AVISO DE PRÓRROGA (Inmediato)
        $mailable = new \App\Mail\Concursos\NuevaProrroga($prorroga);
        
        // Forzamos el envío ahora mismo. 
        // Como cancelamos todo arriba, obtenerProximoTiempo() devolverá 'ahora'.
        self::enviarMasivo(
            $destinatarios,
            $mailable,
            'prorroga',
            "Notificación de prórroga - Concurso #{$prorroga->concurso->numero}"
        );

        // 3. REPROGRAMAR CIERRES (Futuro)
        // Ahora que el aviso ya se encoló para 'ya', creamos los recordatorios 
        // para la nueva fecha de cierre.
        return self::programarEmailsAutomaticosConcurso($prorroga->concurso, $destinatarios);
    }

    /**
     * Programar notificación de cierre automática
     */
    public static function programarCierreAutomatico($concurso, array $destinatarios)
    {
        if (!$concurso->fecha_cierre) return [];

        $fechaCierre = Carbon::parse($concurso->fecha_cierre);
        
        // Solo programar si es en el futuro
        if (!$fechaCierre->isFuture()) return [];

        $mailable = new \App\Mail\Concursos\ConcursoFinalizado($concurso);
        
        return self::programarConTracking(
            $destinatarios,
            $mailable,
            'concurso',
            $concurso->id,
            'notificacion_cierre_automatica',
            $fechaCierre,
            "Cierre automático - Concurso #{$concurso->numero}",
            ['concurso', 'cierre', 'automatico']
        );
    }

    /**
     * Enviar recordatorio manual (inmediato, con tracking)
     */
    public static function enviarRecordatorioManual($concurso, array $destinatarios)
    {
        $mailable = new \App\Mail\Concursos\ProximoCierre($concurso);
        
        return self::programarConTracking(
            $destinatarios,
            $mailable,
            'concurso',
            $concurso->id,
            'recordatorio_manual',
            null, // inmediato
            "Recordatorio manual - Concurso #{$concurso->numero}",
            ['concurso', 'recordatorio', 'manual']
        );
    }

    /**
     * Notificar finalización de concurso (inmediato, con tracking)
     */
    public static function notificarFinalizacionConcurso($concurso, array $destinatarios)
    {
        $mailable = new \App\Mail\Concursos\ConcursoAnalisis($concurso);
        
        return self::programarConTracking(
            $destinatarios,
            $mailable,
            'concurso',
            $concurso->id,
            'notificacion_finalizacion',
            null, // inmediato
            "Finalización - Concurso #{$concurso->numero}",
            ['concurso', 'finalizacion']
        );
    }

    /**
     * Programar todos los emails automáticos de un concurso
     */
    public static function programarEmailsAutomaticosConcurso($concurso, array $destinatarios)
    {
        $jobs = [];
        
        // Recordatorio 48hs
        $jobs48hs = self::programarRecordatorio48hs($concurso, $destinatarios);
        $jobs = array_merge($jobs, $jobs48hs);
        
        // Cierre automático
        $jobsCierre = self::programarCierreAutomatico($concurso, $destinatarios);
        $jobs = array_merge($jobs, $jobsCierre);
        
        return $jobs;
    }

    // ==============================================
    // MÉTODOS DE GESTIÓN
    // ==============================================

    /**
     * Cancelar todos los emails de una entidad
     */
    public static function cancelarEmailsEntidad($entityType, $entityId, $jobType = null)
    {
        return \App\Models\Usuarios\ManagedJob::cancelByEntity($entityType, $entityId, $jobType);
    }

    /**
     * Reprogramar emails de concurso (cancela existentes y programa nuevos)
     */
    public static function reprogramarEmailsConcurso($concurso, array $destinatarios)
    {
        // Cancelar existentes
        self::cancelarEmailsEntidad('concurso', $concurso->id);
        
        // Reprogramar automáticos si está activo
        if ($concurso->estado_id == 2) {
            return self::programarEmailsAutomaticosConcurso($concurso, $destinatarios);
        }
        
        return [];
    }

    /**
     * Agregar nuevos destinatarios a jobs ya programados de un concurso
     * Respeta el espaciado y evita colisiones de tiempo
     */
    public static function agregarDestinatariosAConcurso($concurso, array $nuevosDestinatarios)
    {
        if (empty($nuevosDestinatarios)) return [];

        $jobs = [];

        // Solo agregar si el concurso está activo
        if ($concurso->estado_id != 2) return [];

        // Obtener jobs existentes para saber qué tipos están programados
        $jobsExistentes = \App\Models\Usuarios\ManagedJob::where('entity_type', 'concurso')
            ->where('entity_id', $concurso->id)
            ->where('status', 'pending')
            ->get()
            ->groupBy('job_type');

        // Para cada tipo de job existente, agregar los nuevos destinatarios
        foreach ($jobsExistentes as $jobType => $jobsDelTipo) {
            $primerJob = $jobsDelTipo->first();
            $fechaEjecucion = $primerJob->scheduled_for;

            // Solo agregar si la fecha es futura
            if ($fechaEjecucion->isFuture()) {
                $jobsNuevos = EmailDispatcher::agregarEmailsAConcurso(
                    $concurso,
                    $nuevosDestinatarios,
                    $jobType,
                    null, // mailable se determina automáticamente
                    $fechaEjecucion
                );
                
                $jobs = array_merge($jobs, $jobsNuevos);
            }
        }

        return $jobs;
    }
}