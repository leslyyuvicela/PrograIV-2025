<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplicacion Academica - Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/semantic.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="antialiased">
    <!-- MONTAJE DE VUE AQUÍ -->
    <div id="app">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img width="150" src="img/logo.png" alt="Logo UGB">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#" @click.prevent="abrirFormulario('alumno')">Alumno</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" @click.prevent="abrirFormulario('materia')">Materia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" @click.prevent="abrirFormulario('docente')">Docente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" @click.prevent="abrirFormulario('inscripcionMateria')">Inscripción</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" @click.prevent="abrirFormulario('matricula')">Matricula</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <!-- Cambio de v-show a v-if -->
            <alumno v-if="forms.alumno.mostrar" :forms="forms" ref="alumno" @buscar="buscar('buscar_alumno', 'listarAlumnos')"></alumno>
            <buscar_alumno v-if="forms.buscarAlumno.mostrar" ref="buscar_alumno" @modificar="modificar('alumno', 'modificarAlumno', $event)"></buscar_alumno>

            <docente v-if="forms.docente.mostrar" :forms="forms" ref="docente" @buscar="buscar('buscar_docente', 'listarDocentes')"></docente>
            <buscar_docente v-if="forms.buscarDocente.mostrar" ref="buscar_docente" @modificar="modificar('docente', 'modificarDocente', $event)"></buscar_docente>

            <materia v-if="forms.materia.mostrar" :forms="forms" ref="materia" @buscar="buscar('buscar_materia', 'listarMaterias')"></materia>
            <buscar_materia v-if="forms.buscarMateria.mostrar" ref="buscar_materia" @modificar="modificar('materia', 'modificarMateria', $event)"></buscar_materia>

            <inscripcion_materia v-if="forms.inscripcionMateria.mostrar" :forms="forms" ref="inscripcion_materia" @buscar="buscar('buscar_materias_inscritas', 'listarInscripciones')"></inscripcion_materia>
            <buscar_materias_inscritas v-if="forms.buscarMateriasInscritas.mostrar" ref="buscar_materias_inscritas" @modificar="modificar('inscripcionMateria', 'modificarInscripcionMateria', $event)"></buscar_materias_inscritas>

            <matricula v-if="forms.matricula.mostrar" :forms="forms" ref="matricula" @buscar="buscar('buscar_matriculas', 'listarMatriculas')"></matricula>
            <buscar_matriculas v-if="forms.buscarMatriculas.mostrar" ref="buscar_matriculas" @modificar="modificar('matricula', 'modificarMatricula', $event)"></buscar_matriculas>
        </div>
    </div>

    @vite('resources/js/app.js')
</body>
</html>

