# Dokumentasi API Mobile (NextClean)

Dokumen ini berisi spesifikasi OpenAPI (Swagger) beserta contoh-contoh *body request* untuk mempermudah integrasi dengan aplikasi *mobile*.

## Contoh *Request Body* & Cara Pemanggilan (cURL)

### 1. Login (`POST /api/login`)
Mengautentikasi pengguna dan mendapatkan Sanctum *Bearer Token*.
```bash
curl -X POST http://nextclean.test/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "kasir@nextclean.com",
    "password": "password123"
  }'
```

### 2. Get Profile (`GET /api/profile`)
Mendapatkan data Karyawan yang sedang *login*.
```bash
curl -X GET http://nextclean.test/api/profile \
  -H "Authorization: Bearer {token_anda_disini}" \
  -H "Accept: application/json"
```

### 3. Clock In Absensi (`POST /api/attendance/clock-in`)
Melakukan absensi masuk. **Wajib menggunakan `multipart/form-data`** karena ada unggahan *file* foto wajah.
```bash
curl -X POST http://nextclean.test/api/attendance/clock-in \
  -H "Authorization: Bearer {token_anda_disini}" \
  -H "Accept: application/json" \
  -F "latitude=-6.200000" \
  -F "longitude=106.816666" \
  -F "face_image=@/path/to/foto_wajah.jpg"
```

### 4. Clock Out Absensi (`POST /api/attendance/clock-out`)
Melakukan absensi pulang untuk hari ini.
```bash
curl -X POST http://nextclean.test/api/attendance/clock-out \
  -H "Authorization: Bearer {token_anda_disini}" \
  -H "Accept: application/json"
```

---

## Swagger / OpenAPI 3.0 Specification

Anda bisa menyalin (copy) kode YAML di bawah ini dan menempelkannya ke [editor.swagger.io](https://editor.swagger.io/) untuk melihat *interface* Swagger UI yang interaktif.

```yaml
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
```
