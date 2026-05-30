<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextClean API Documentation</title>
    <!-- Swagger UI CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
    <style>
        body { margin: 0; padding: 0; }
        #swagger-ui { max-width: 1440px; margin: 0 auto; }
        .topbar { display: none; } /* Hide the top Swagger bar */
    </style>
</head>
<body>
    <div id="swagger-ui"></div>

    <!-- Swagger UI JS -->
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js"></script>
    <!-- js-yaml library to parse YAML string -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-yaml/4.1.0/js-yaml.min.js"></script>
    
    <script>
        window.onload = function() {
            // The OpenAPI YAML Specification
            const specYaml = `
openapi: 3.0.0
info:
  title: NextClean Mobile API
  description: API Endpoint untuk autentikasi dan absensi aplikasi mobile karyawan NextClean.
  version: 1.0.0
servers:
  - url: http://nextclean.test/api
    description: Local Server
paths:
  /login:
    post:
      summary: Login Karyawan
      tags:
        - Auth
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required:
                - email
                - password
              properties:
                email:
                  type: string
                  format: email
                  example: admin@nextclean.com
                password:
                  type: string
                  format: password
                  example: secret123
      responses:
        '200':
          description: Berhasil login
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: boolean
                    example: true
                  message:
                    type: string
                    example: Berhasil login.
                  data:
                    type: object
                    properties:
                      access_token:
                        type: string
                      token_type:
                        type: string
                        example: Bearer
                      user:
                        type: object
        '401':
          description: Kredensial tidak valid

  /profile:
    get:
      summary: Mendapatkan data profile karyawan
      tags:
        - Profile
      security:
        - bearerAuth: []
      responses:
        '200':
          description: Berhasil mengambil profil

  /attendance/clock-in:
    post:
      summary: Melakukan absen masuk (Clock In)
      tags:
        - Attendance
      security:
        - bearerAuth: []
      requestBody:
        required: true
        content:
          multipart/form-data:
            schema:
              type: object
              required:
                - latitude
                - longitude
                - face_image
              properties:
                latitude:
                  type: string
                  example: "-6.200000"
                longitude:
                  type: string
                  example: "106.816666"
                face_image:
                  type: string
                  format: binary
                  description: File foto wajah (maksimal 2MB)
      responses:
        '200':
          description: Absen masuk berhasil
        '400':
          description: Sudah absen atau akun tidak terikat
        '422':
          description: Validasi error (seperti foto terlalu besar)

  /attendance/clock-out:
    post:
      summary: Melakukan absen pulang (Clock Out)
      tags:
        - Attendance
      security:
        - bearerAuth: []
      responses:
        '200':
          description: Absen pulang berhasil
        '400':
          description: Belum absen masuk atau sudah absen pulang

components:
  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT
`;

            // Parse YAML to JSON
            const specObj = jsyaml.load(specYaml);

            // Initialize Swagger UI
            const ui = SwaggerUIBundle({
                spec: specObj,
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            });
            window.ui = ui;
        };
    </script>
</body>
</html>
