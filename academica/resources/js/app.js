import './bootstrap';
import { createApp } from 'vue';
import Dexie from 'dexie';
import alumno from './components/AlumnoComponent.vue';
import buscar_alumno from './components/BusquedaAlumnoComponent.vue';
import docente from './components/DocenteComponent.vue';
import buscar_docente from './components/BusquedaDocenteComponent.vue';
import materia from './components/MateriaComponent.vue';
import buscar_materia from './components/BusquedaMateriaComponent.vue';
import inscripcion_materia from './components/InscripcionMateriasComponent.vue';
import buscar_materias_inscritas from './components/BuscarMateriasInscritasComponent.vue';
import matricula from './components/MatriculaComponent.vue';
import buscar_matriculas from './components/BusquedaMatriculasComponent.vue';

window.db = new Dexie('db_academico');

const app = createApp({
    components: {
        alumno,
        buscar_alumno,
        docente, 
        buscar_docente,
        materia,
        buscar_materia,
        inscripcion_materia,
        buscar_materias_inscritas,
        matricula,
        buscar_matriculas
    },
    data() {
        return {
            forms: {
                alumno: { mostrar: false },
                buscarAlumno: { mostrar: false },
                materia: { mostrar: false },
                buscarMateria: { mostrar: false },
                docente: { mostrar: false },
                buscarDocente: { mostrar: false },
                inscripcionMateria: { mostrar: false },  
                buscarMateriasInscritas: { mostrar: false },
                matricula: { mostrar: false },
                buscarMatriculas: { mostrar: false }
            }
        };
    },
    methods: {
        buscar(form, metodo) {
            this.$refs[form][metodo]();
        },
        abrirFormulario(componente) {
            // Primero, cerramos todos los formularios
            Object.keys(this.forms).forEach((key) => {
                this.forms[key].mostrar = false;
            });
            // Luego, abrimos el formulario solicitado
            this.forms[componente].mostrar = true;
        },
        modificar(form, metodo, datos) {
            this.$refs[form][metodo](datos);
        }
    },
    created() {
        db.version(1).stores({
            alumnos: 'codigo_transaccion, codigo, nombre, direccion, telefono, email, hash',
            materias: 'codigo_transaccion, codigo, nombre, uv, hash',
        });
    }
});

app.mount('#app');
