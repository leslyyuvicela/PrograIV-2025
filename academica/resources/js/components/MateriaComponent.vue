<template>
  <div class="container py-4">
    <form id="frmMateria" name="frmMateria" @submit.prevent="guardarMateria">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Registro de Materias</h5>
        </div>
        <div class="card-body">
          <div class="mb-3 row">
            <label for="txtCodigoMateria" class="col-sm-2 col-form-label">Código</label>
            <div class="col-sm-4">
              <input required v-model="materia.codigo" type="text" id="txtCodigoMateria" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtNombreMateria" class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-6">
              <input required pattern="[A-Za-zñÑáéíóú ]{3,150}" v-model="materia.nombre" type="text" id="txtNombreMateria" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtUvMateria" class="col-sm-2 col-form-label">UV</label>
            <div class="col-sm-2">
              <input required min="1" max="10" v-model="materia.uv" type="number" id="txtUvMateria" class="form-control" />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="selectDocente" class="col-sm-2 col-form-label">Docente</label>
            <div class="col-sm-6">
              <select v-model="materia.id_docente" id="selectDocente" class="form-select" required>
                <option value="" disabled>Seleccione un docente</option>
                <option v-for="docente in docentes" :key="docente.codigo" :value="docente.codigo">
                  {{ docente.nombre }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="card-footer bg-light text-end">
          <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save me-1"></i> Guardar
            </button>
            <button type="reset" @click="nuevoMateria" class="btn btn-secondary">
              <i class="bi bi-plus-circle me-1"></i> Nuevo
            </button>
            <button type="button" @click="buscarMateria" class="btn btn-info text-white">
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
      materia: {
        codigo: '',
        nombre: '',
        uv: '',
        id_docente: '',
        codigo_transaccion: uuidv4(),
      },
      docentes: [],
    };
  },
  methods: {
    buscarMateria() {
      this.forms.buscarMateria.mostrar = !this.forms.buscarMateria.mostrar;
      this.$emit('buscar');
    },
    modificarMateria(materia) {
      this.accion = 'modificar';
      this.materia = { ...materia };
    },
    async guardarMateria() {
      let materia = { ...this.materia },
          metodo = this.accion === 'modificar' ? "PUT" : "POST";

      materia.hash = CryptoJS.SHA256(JSON.stringify({
        codigo: materia.codigo,
        nombre: materia.nombre,
        uv: materia.uv,
        id_docente: materia.id_docente
      })).toString();

      db.materias.put(materia);

      try {
        const response = await axios({
          method: metodo,
          url: `materia`,
          data: materia,
          headers: { 'Content-Type': 'application/json' }
        });

        if (response.data.msg !== 'ok') {
          alertify.error(response.data.msg);
        } else {
          this.nuevoMateria();
          this.$emit('buscar');
        }
      } catch (error) {
        alertify.error('Error al guardar la materia: ' + error);
      }
    },
    nuevoMateria() {
      this.accion = 'nuevo';
      this.materia = {
        codigo: '',
        nombre: '',
        uv: '',
        id_docente: '',
        codigo_transaccion: uuidv4(),
      };
    },
    async cargarDocentes() {
      try {
        const response = await axios.get('docente');
        this.docentes = response.data;
        db.docentes.bulkAdd(this.docentes);
      } catch (error) {
        alertify.error('Error al cargar docentes: ' + error);
      }
    }
  },
  created() {
    this.cargarDocentes();
  }
};
</script>
