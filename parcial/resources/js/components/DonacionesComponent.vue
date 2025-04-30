<template>
  <div class="container mt-5">
    <h2 class="mb-4">Gestión de Donaciones</h2>

    <!-- FORMULARIO -->
    <form @submit.prevent="submitForm" class="mb-4">
      <div class="row">
        <!-- Tipo -->
        <div class="col-md-4 mb-3">
          <label for="tipo" class="form-label">Tipo</label>
          <select v-model="form.tipo" id="tipo" class="form-select" required>
            <option value="">Seleccione</option>
            <option value="bien">Bien</option>
            <option value="servicio">Servicio</option>
            <option value="económica">Económica</option>
          </select>
        </div>
        
        <!-- Descripción -->
        <div class="col-md-4 mb-3">
          <label for="descripcion" class="form-label">Descripción</label>
          <input v-model="form.descripcion" id="descripcion" type="text" class="form-control" required />
        </div>

        <!-- Valor estimado -->
        <div class="col-md-4 mb-3">
          <label for="valor_estimado" class="form-label">Valor estimado</label>
          <input v-model="form.valor_estimado" id="valor_estimado" type="number" class="form-control" />
        </div>

        <!-- Fecha de recepción -->
        <div class="col-md-4 mb-3">
          <label for="fecha_recepcion" class="form-label">Fecha de recepción</label>
          <input v-model="form.fecha_recepcion" id="fecha_recepcion" type="date" class="form-control" required />
        </div>

        <!-- Donante -->
        <div class="col-md-4 mb-3">
          <label for="donante_id" class="form-label">Donante</label>
          <select v-model="form.donante_id" id="donante_id" class="form-select" required>
            <option v-for="donante in donantes" :value="donante.id" :key="donante.id">{{ donante.nombre }}</option>
          </select>
        </div>

        <!-- Administrador -->
        <div class="col-md-4 mb-3">
          <label for="administrador_id" class="form-label">Administrador</label>
          <select v-model="form.administrador_id" id="administrador_id" class="form-select" required>
            <option v-for="admin in administradores" :value="admin.id" :key="admin.id">{{ admin.nombre }}</option>
          </select>
        </div>
      </div>

      <!-- Botones -->
      <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-primary me-2">{{ form.id ? 'Actualizar' : 'Registrar' }}</button>
        <button type="button" class="btn btn-secondary" @click="resetForm" v-if="form.id">Cancelar</button>
      </div>
    </form>

    <!-- BUSQUEDA -->
    <div class="mb-4 row">
      <div class="col-md-4">
        <input v-model="filtro.tipo" type="text" class="form-control" placeholder="Buscar por tipo..." @input="fetchDonaciones" />
      </div>
      <div class="col-md-4">
        <input v-model="filtro.donante" type="text" class="form-control" placeholder="Buscar por donante..." @input="fetchDonaciones" />
      </div>
    </div>

    <!-- LISTADO -->
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <thead class="table-light">
          <tr>
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Valor</th>
            <th>Fecha</th>
            <th>Donante</th>
            <th>Administrador</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="donacion in donaciones" :key="donacion.id">
            <td>{{ donacion.tipo }}</td>
            <td>{{ donacion.descripcion }}</td>
            <td>{{ donacion.valor_estimado }}</td>
            <td>{{ donacion.fecha_recepcion }}</td>
            <td>{{ donacion.donante.nombre }}</td>
            <td>{{ donacion.administrador.nombre }}</td>
            <td>
              <button @click="editDonacion(donacion)" class="btn btn-warning btn-sm">Editar</button>
              <button @click="deleteDonacion(donacion.id)" class="btn btn-danger btn-sm ms-2">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        tipo: '',
        descripcion: '',
        valor_estimado: '',
        fecha_recepcion: '',
        donante_id: '',
        administrador_id: '',
        id: null,
      },
      filtro: {
        tipo: '',
        donante: '',
      },
      donaciones: [],
      donantes: [],
      administradores: [],
    };
  },
  methods: {
    fetchDonaciones() {
      axios.get('/api/donaciones', { params: this.filtro })
        .then(res => {
          console.log("Donaciones recibidas:", res.data);
          this.donaciones = res.data;
        })
        .catch(error => {
          console.error("Error al cargar donaciones:", error);
        });
    },
    fetchDonantes() {
      axios.get('/api/donantes')
        .then(res => {
          console.log("Donantes:", res.data);
          this.donantes = res.data;
        })
        .catch(error => {
          console.error("Error al cargar donantes:", error);
        });
    },
    fetchAdministradores() {
      axios.get('/api/administradores')
        .then(res => {
          console.log("Administradores:", res.data);
          this.administradores = res.data;
        })
        .catch(error => {
          console.error("Error al cargar administradores:", error);
        });
    },
    submitForm() {
      const metodo = this.form.id ? 'put' : 'post';
      const url = this.form.id ? `/api/donaciones/${this.form.id}` : '/api/donaciones';

      axios[metodo](url, this.form)
        .then(() => {
          this.fetchDonaciones();
          this.resetForm();
        })
        .catch(error => {
          console.error("Error al guardar donación:", error);
        });
    },
    editDonacion(donacion) {
      this.form = {
        ...donacion,
        donante_id: donacion.donante?.id ?? '',
        administrador_id: donacion.administrador?.id ?? '',
        id: donacion.id
      };
    },
    deleteDonacion(id) {
      if (confirm('¿Estás seguro de eliminar esta donación?')) {
        axios.delete(`/api/donaciones/${id}`)
          .then(() => this.fetchDonaciones())
          .catch(error => {
            console.error("Error al eliminar donación:", error);
          });
      }
    },
    resetForm() {
      this.form = {
        tipo: '',
        descripcion: '',
        valor_estimado: '',
        fecha_recepcion: '',
        donante_id: '',
        administrador_id: '',
        id: null,
      };
    }
  },
  mounted() {
    console.log("Componente montado");
    this.fetchDonaciones();
    this.fetchDonantes();
    this.fetchAdministradores();
  }
};
</script>
