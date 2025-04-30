<template>
  <div class="container py-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Buscar Materias</h5>
      </div>
      <div class="card-body">
        <div class="row align-items-end mb-3">
          <div class="col-md-4">
            <label class="form-label">Buscar por</label>
            <select v-model="buscarTipo" class="form-select">
              <option value="codigo">Código</option>
              <option value="nombre">Nombre</option>
              <option value="uv">UV</option>
              <option value="docente">Docente</option>
            </select>
          </div>
          <div class="col-md-8">
            <label class="form-label">Término de búsqueda</label>
            <input type="text" @keyup="listarMaterias" v-model="buscar" class="form-control" placeholder="Escribe para buscar..." />
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>UV</th>
                <th>Docente</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="materia in materias" :key="materia.codigo_transaccion" @click="modificarMateria(materia)">
                <td>{{ materia.codigo }}</td>
                <td>{{ materia.nombre }}</td>
                <td>{{ materia.uv }}</td>
                <td>{{ obtenerNombreDocente(materia.id_docente) }}</td>
                <td>
                  <button class="btn btn-sm btn-danger" @click.stop="eliminarMateria(materia)">
                    <i class="bi bi-trash"></i> Eliminar
                  </button>
                </td>
              </tr>
              <tr v-if="materias.length === 0">
                <td colspan="5" class="text-muted">No se encontraron resultados.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import alertify from 'alertifyjs';

export default {
  data() {
    return {
      buscar: '',
      buscarTipo: 'nombre',
      materias: [],
      docentes: [],
    };
  },
  methods: {
    modificarMateria(materia) {
      this.$emit('modificar', materia);
    },
    eliminarMateria(materia) {
      alertify.confirm('Eliminar Materia', `¿Está seguro de eliminar la materia ${materia.nombre}?`, async () => {
        try {
          const response = await axios.delete(`materia`, { data: materia });
          if (response.data.msg !== 'ok') {
            alertify.error(response.data.msg);
          } else {
            db.materias.delete(materia.codigo_transaccion);
            this.listarMaterias();
            alertify.success(`Materia ${materia.nombre} eliminada`);
          }
        } catch (error) {
          alertify.error('Error al eliminar la materia: ' + error);
        }
      }, () => {});
    },
    obtenerNombreDocente(idDocente) {
      const docente = this.docentes.find(d => d.codigo === idDocente);
      return docente ? docente.nombre : 'No asignado';
    },
    async listarMaterias() {
      let todas = await db.materias.toArray();

      if (todas.length === 0) {
        try {
          const response = await axios.get(`materia`);
          todas = response.data;
          db.materias.bulkAdd(todas);
        } catch (error) {
          alertify.error('Error al cargar materias: ' + error);
          return;
        }
      }

      this.materias = todas.filter(materia => {
        if (this.buscarTipo === 'docente') {
          const nombreDocente = this.obtenerNombreDocente(materia.id_docente).toLowerCase();
          return nombreDocente.includes(this.buscar.toLowerCase());
        } else {
          return materia[this.buscarTipo]?.toString().toLowerCase().includes(this.buscar.toLowerCase());
        }
      });
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
    this.listarMaterias();
  }
};
</script>
