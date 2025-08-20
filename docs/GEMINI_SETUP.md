# Configuración de Google Gemini API

## Variables de Entorno Requeridas

Para que TitoBot funcione correctamente con Google Gemini, necesitas agregar la siguiente variable a tu archivo `.env`:

```env
GEMINI_API_KEY=tu_api_key_de_google_gemini_aqui
```

## Cómo obtener tu API Key de Google Gemini

1. Ve a [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Inicia sesión con tu cuenta de Google
3. Haz clic en "Create API Key"
4. Copia la API key generada
5. Pégala en tu archivo `.env`

## Verificación

Para verificar que la configuración funciona correctamente, puedes:

1. Agregar la variable al archivo `.env`
2. Reiniciar el servidor web
3. Ir a la página de TitoBot
4. Hacer una pregunta simple como "Hola"

## Notas Importantes

- La API key debe mantenerse segura y no debe compartirse
- El archivo `.env` no debe subirse al control de versiones
- Si tienes problemas, verifica que la API key sea válida y tenga los permisos correctos

## Modelo Utilizado

TitoBot utiliza el modelo `gemini-2.0-flash` que es:
- Rápido y eficiente
- Optimizado para conversaciones
- Capaz de procesar texto en español
- Gratuito para uso básico 