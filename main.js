const { createApp } = Vue;

createApp({
    data() {
        return {
            busqueda: '',
            formulario: {
                codigo: '',
                nombre: '',
                direccion: '',
                municipio: '',
                departamento: '',
                distrito: '',
                telefono: '',
                fechaNacimiento: '',
                sexo: ''
            },
            alumnos: [],
            campos: [
                { id: 'codigo', label: 'Código', type: 'text' },
                { id: 'nombre', label: 'Nombre', type: 'text' },
                { id: 'direccion', label: 'Dirección', type: 'text' },
                { id: 'municipio', label: 'Municipio', type: 'text' },
                { id: 'departamento', label: 'Departamento', type: 'text' },
                { id: 'distrito', label: 'Distrito', type: 'text' },
                { id: 'telefono', label: 'Teléfono', type: 'text' },
                { id: 'fechaNacimiento', label: 'Fecha de Nacimiento', type: 'date' },
                { id: 'sexo', label: 'Sexo', type: 'select', options: ['Masculino', 'Femenino'] }
            ]
        };
    },
    computed: {
        alumnosFiltrados() {
            return this.alumnos.filter(alumno =>
                alumno.nombre.toLowerCase().includes(this.busqueda.toLowerCase()) ||
                alumno.codigo.toLowerCase().includes(this.busqueda.toLowerCase())
            );
        }
    },
    methods: {
        guardarAlumno() {
            const nuevoAlumno = { ...this.formulario };
            localStorage.setItem(nuevoAlumno.codigo, JSON.stringify(nuevoAlumno));
            this.listarAlumnos();
            this.limpiarFormulario();
        },
        listarAlumnos() {
            this.alumnos = [];
            for (let i = 0; i < localStorage.length; i++) {
                let clave = localStorage.key(i);
                let valor = JSON.parse(localStorage.getItem(clave));
                this.alumnos.push(valor);
            }
        },
        verAlumno(alumno) {
            this.formulario = { ...alumno };
        },
        eliminarAlumno(codigo) {
            if (confirm('¿Seguro que deseas eliminar este alumno?')) {
                localStorage.removeItem(codigo);
                this.listarAlumnos();
            }
        },
        limpiarFormulario() {
            this.formulario = {
                codigo: '',
                nombre: '',
                direccion: '',
                municipio: '',
                departamento: '',
                distrito: '',
                telefono: '',
                fechaNacimiento: '',
                sexo: ''
            };
        }
    },
    created() {
        this.listarAlumnos();
    }
}).mount('#app');
