<template>
  <div class="container py-4">
    <form id="frmAlumno" name="frmAlumno" @submit.prevent="guardarAlumno">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Registro de Alumnos</h5>
        </div>
        <div class="card-body">
          <div class="mb-3 row">
            <label for="txtCodigoAlumno" class="col-sm-2 col-form-label">Código</label>
            <div class="col-sm-4">
              <input required v-model="alumno.codigo" type="text" id="txtCodigoAlumno" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtNombreAlumno" class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-6">
              <input required pattern="[A-Za-zñÑáéíóú ]{3,150}" v-model="alumno.nombre" type="text" id="txtNombreAlumno" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtDireccionAlumno" class="col-sm-2 col-form-label">Dirección</label>
            <div class="col-sm-8">
              <input required v-model="alumno.direccion" type="text" id="txtDireccionAlumno" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtTelefonoAlumno" class="col-sm-2 col-form-label">Teléfono</label>
            <div class="col-sm-4">
              <input v-model="alumno.telefono" type="text" id="txtTelefonoAlumno" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtEmailAlumno" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-6">
              <input v-model="alumno.email" type="email" id="txtEmailAlumno" class="form-control" />
            </div>
          </div>
        </div>

        <div class="card-footer bg-light text-end">
          <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save me-1"></i> Guardar
            </button>
            <button type="reset" @click="nuevoAlumno" class="btn btn-secondary">
              <i class="bi bi-plus-circle me-1"></i> Nuevo
            </button>
            <button type="button" @click="buscarAlumno" class="btn btn-info text-white">
              <i class="bi bi-search me-1"></i> Buscar
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
    import axios from 'axios';
    import alertify from 'alertifyjs';
    import { v4 as uuidv4 } from 'uuid';
    import CryptoJS from 'crypto-js';
    export default {
        props: ['forms'],
        data() {
            return {
                accion: 'nuevo',
                alumno : {
                    codigo: '',
                    nombre: '',
                    direccion: '',
                    telefono: '',
                    email: '',
                    codigo_transaccion: uuidv4()
                },
            }
        },
        methods: {
            buscarAlumno() {
                this.forms.buscarAlumno.mostrar = !this.forms.buscarAlumno.mostrar;
                this.$emit('buscar');
            },
            modificarAlumno(alumno) {
                this.accion = 'modificar';
                this.alumno = {...alumno};
            },
            guardarAlumno() {
                let alumno = {...this.alumno},
                    metodo="POST";
                alumno.hash = CryptoJS.SHA256(JSON.stringify({
                    codigo: alumno.codigo,
                    nombre: alumno.nombre,
                    direccion: alumno.direccion,
                    telefono: alumno.telefono,
                    email: alumno.email
                })).toString();
                db.alumnos.put(alumno);
                if( this.accion=='modificar' ){
                    metodo = "PUT";
                }
                axios ({
                    method: metodo,
                    url: `alumno`,
                    data: alumno,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if( response.data.msg != 'ok' ){
                        alertify.error(response.data.msg);
                    }else{
                        this.nuevoAlumno();
                        this.$emit('buscar');
                    }
                }).catch(error => {
                    alertify.error('Error al guardar el alumno: ' + error);
                });
            },
            nuevoAlumno() {
                this.accion = 'nuevo';
                this.alumno = {
                    codigo: '',
                    nombre: '',
                    direccion: '',
                    telefono: '',
                    email: '',
                    codigo_transaccion: uuidv4()
                };
            }
        },
    }
</script>