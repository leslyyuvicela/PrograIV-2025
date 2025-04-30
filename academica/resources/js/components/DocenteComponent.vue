<template>
  <div class="container py-4">
    <form id="frmDocente" name="frmDocente" @submit.prevent="guardarDocente">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Registro de Docentes</h5>
        </div>
        <div class="card-body">
          <div class="mb-3 row">
            <label for="txtCodigoDocente" class="col-sm-2 col-form-label">Código</label>
            <div class="col-sm-4">
              <input required v-model="docente.codigo" type="text" id="txtCodigoDocente" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtNombreDocente" class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-6">
              <input required pattern="[A-Za-zñÑáéíóú ]{3,150}" v-model="docente.nombre" type="text" id="txtNombreDocente" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtDireccionDocente" class="col-sm-2 col-form-label">Dirección</label>
            <div class="col-sm-8">
              <input required v-model="docente.direccion" type="text" id="txtDireccionDocente" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtTelefonoDocente" class="col-sm-2 col-form-label">Teléfono</label>
            <div class="col-sm-4">
              <input v-model="docente.telefono" type="text" id="txtTelefonoDocente" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtEmailDocente" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-6">
              <input v-model="docente.email" type="email" id="txtEmailDocente" class="form-control" />
            </div>
          </div>
        </div>

        <div class="card-footer bg-light text-end">
          <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save me-1"></i> Guardar
            </button>
            <button type="reset" @click="nuevoDocente" class="btn btn-secondary">
              <i class="bi bi-plus-circle me-1"></i> Nuevo
            </button>
            <button type="button" @click="buscarDocente" class="btn btn-info text-white">
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
      docente: {
        codigo: '',
        nombre: '',
        direccion: '',
        telefono: '',
        email: '',
        codigo_transaccion: uuidv4()
      },
    };
  },
  methods: {
    buscarDocente() {
      this.forms.buscarDocente.mostrar = !this.forms.buscarDocente.mostrar;
      this.$emit('buscar');
    },
    modificarDocente(docente) {
      this.accion = 'modificar';
      this.docente = { ...docente };
    },
    guardarDocente() {
      let docente = { ...this.docente },
        metodo = "POST";
      docente.hash = CryptoJS.SHA256(JSON.stringify({
        codigo: docente.codigo,
        nombre: docente.nombre,
        direccion: docente.direccion,
        telefono: docente.telefono,
        email: docente.email
      })).toString();
      db.docentes.put(docente);
      if (this.accion === 'modificar') {
        metodo = "PUT";
      }
      axios({
        method: metodo,
        url: `docente`,
        data: docente,
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(response => {
        if (response.data.msg !== 'ok') {
          alertify.error(response.data.msg);
        } else {
          this.nuevoDocente();
          this.$emit('buscar');
        }
      }).catch(error => {
        alertify.error('Error al guardar el docente: ' + error);
      });
    },
    nuevoDocente() {
      this.accion = 'nuevo';
      this.docente = {
        codigo: '',
        nombre: '',
        direccion: '',
        telefono: '',
        email: '',
        codigo_transaccion: uuidv4()
      };
    }
  }
};
</script>
