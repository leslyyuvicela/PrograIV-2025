<template>
  <div class="container py-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Buscar Docentes</h5>
      </div>
      <div class="card-body">
        <div class="row align-items-end mb-3">
          <div class="col-md-4">
            <label class="form-label">Buscar por</label>
            <select v-model="buscarTipo" class="form-select">
              <option value="codigo">Código</option>
              <option value="nombre">Nombre</option>
              <option value="direccion">Dirección</option>
              <option value="telefono">Teléfono</option>
              <option value="email">Email</option>
            </select>
          </div>
          <div class="col-md-8">
            <label class="form-label">Término de búsqueda</label>
            <input type="text" @keyup="listarDocentes" v-model="buscar" class="form-control" placeholder="Escribe para buscar..." />
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="docente in docentes" :key="docente.codigo_transaccion" @click="modificarDocente(docente)">
                <td>{{ docente.codigo }}</td>
                <td>{{ docente.nombre }}</td>
                <td>{{ docente.direccion }}</td>
                <td>{{ docente.telefono }}</td>
                <td>{{ docente.email }}</td>
                <td>
                  <button class="btn btn-sm btn-danger" @click.stop="eliminarDocente(docente)">
                    <i class="bi bi-trash"></i> Eliminar
                  </button>
                </td>
              </tr>
              <tr v-if="docentes.length === 0">
                <td colspan="6" class="text-muted">No se encontraron resultados.</td>
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
      docentes: [],
    };
  },
  methods: {
    modificarDocente(docente) {
      this.$emit('modificar', docente);
    },
    eliminarDocente(docente) {
      alertify.confirm('Eliminar Docente', `¿Está seguro de eliminar al docente ${docente.nombre}?`, async () => {
        try {
          const response = await axios.delete(`docente`, { data: docente });
          if (response.data.msg !== 'ok') {
            alertify.error(response.data.msg);
          } else {
            db.docentes.delete(docente.codigo_transaccion);
            this.listarDocentes();
            alertify.success(`Docente ${docente.nombre} eliminado`);
          }
        } catch (error) {
          alertify.error('Error al eliminar el docente: ' + error);
        }
      }, () => {});
    },
    async listarDocentes() {
      this.docentes = await db.docentes
        .filter(docente =>
          docente[this.buscarTipo]
            ?.toLowerCase()
            .includes(this.buscar.toLowerCase())
        )
        .toArray();

      if (this.docentes.length < 1) {
        try {
          const response = await axios.get(`docente`);
          this.docentes = response.data;
          db.docentes.bulkAdd(response.data);
        } catch (error) {
          alertify.error('Error al cargar docentes: ' + error);
        }
      }
    },
  },
  created() {
    this.listarDocentes();
  },
};
</script>
