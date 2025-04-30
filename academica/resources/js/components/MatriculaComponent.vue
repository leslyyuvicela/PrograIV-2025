<template>
  <div class="container py-4">
    <form @submit.prevent="guardarMatricula">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Registro de Matrículas</h5>
        </div>
        <div class="card-body">
          <div class="mb-3 row">
            <label for="cmbAlumno" class="col-sm-2 col-form-label">Alumno</label>
            <div class="col-sm-6">
              <select v-model="idAlumno" id="cmbAlumno" class="form-select" @change="actualizarAlumno" required>
                <option disabled value="">Seleccione un alumno</option>
                <option v-for="alumno in alumnos" :key="alumno.idAlumno" :value="alumno.idAlumno">
                  {{ alumno.nombre }}
                </option>
              </select>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="txtFecha" class="col-sm-2 col-form-label">Fecha</label>
            <div class="col-sm-4">
              <input type="date" v-model="fecha" id="txtFecha" class="form-control" required />
            </div>
          </div>

          <div class="mb-3 row">
            <label for="cmbCarrera" class="col-sm-2 col-form-label">Carrera</label>
            <div class="col-sm-6">
              <select v-model="carrera" id="cmbCarrera" class="form-select" required>
                <option disabled value="">Seleccione una carrera</option>
                <option v-for="c in carreras" :key="c" :value="c">{{ c }}</option>
              </select>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="cmbPeriodo" class="col-sm-2 col-form-label">Periodo</label>
            <div class="col-sm-4">
              <select v-model="periodo" id="cmbPeriodo" class="form-select" required>
                <option disabled value="">Seleccione un periodo</option>
                <option v-for="p in periodos" :key="p" :value="p">{{ p }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="card-footer bg-light text-end">
          <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save me-1"></i> Guardar
            </button>
            <button type="reset" @click="nuevaMatricula" class="btn btn-secondary">
              <i class="bi bi-plus-circle me-1"></i> Nuevo
            </button>
            <button type="button" @click="buscarMatricula" class="btn btn-info text-white">
              <i class="bi bi-search me-1"></i> Buscar
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: ['forms'],
  data() {
    return {
      accion: 'nuevo',
      alumnos: [],
      periodos: ['I', 'II', 'III', 'IV'],
      carreras: [
        'Ingeniería en Sistemas',
        'Ingeniería en Electrónica',
        'Ingeniería en Mecánica',
        'Ingeniería Industrial'
      ],
      idMatricula: '',
      idAlumno: '',
      fecha: '',
      periodo: '',
      carrera: '',
      nombreAlumno: '',
      codigoAlumno: ''
    };
  },
  methods: {
    buscarMatricula() {
      this.forms.buscarMatriculas.mostrar = true;
      this.$emit('buscar');
    },
    modificarMatricula(matricula) {
      this.accion = 'modificar';
      Object.assign(this, matricula);
    },
    guardarMatricula() {
      const matricula = {
        idAlumno: this.idAlumno,
        fecha: this.fecha,
        periodo: this.periodo,
        carrera: this.carrera,
        nombreAlumno: this.nombreAlumno,
        codigoAlumno: this.codigoAlumno
      };
      if (this.accion === 'modificar') {
        matricula.idMatricula = this.idMatricula;
      }
      // db.matriculas.put(matricula);
      this.nuevaMatricula();
      this.$emit('buscar');
    },
    nuevaMatricula() {
      this.accion = 'nuevo';
      this.idMatricula = '';
      this.idAlumno = '';
      this.fecha = '';
      this.periodo = '';
      this.carrera = '';
      this.nombreAlumno = '';
      this.codigoAlumno = '';
    },
    actualizarAlumno() {
      const alumno = this.alumnos.find(a => a.idAlumno === this.idAlumno);
      if (alumno) {
        this.nombreAlumno = alumno.nombre;
        this.codigoAlumno = alumno.codigo;
      }
    },
    cargarDatos() {
      // Descomentar cuando se conecte a DB
      // db.alumnos.toArray().then(alumnos => {
      //   this.alumnos = alumnos;
      // });
    }
  },
  created() {
    this.cargarDatos();
  }
};
</script>
