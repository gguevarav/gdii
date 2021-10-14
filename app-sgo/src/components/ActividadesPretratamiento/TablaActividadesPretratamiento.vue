<template>
  <div class="text-center">
    <!-- Tabla de actividades de pretratamiento -->
    <v-data-table
        dense
        :headers="encabezadosTabla"
        :items="datosTabla"
        :items-per-page="10"
        :search="search"
        sort-by="NombreActividad"
        class="elevation-1">
      <template
          v-slot:top>
        <v-toolbar
            flat
            color="white">
          <v-toolbar-title>
            Listado de actividades de pretratamiento
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <!-- Dialog de botones de agregar y recargar -->
          <v-dialog>
            <template
                v-slot:activator="{ on, attrs }">
              <v-btn
                  text
                  class="mb-2"
                  @click="inicializar">
                <v-icon>
                  mdi-reload
                </v-icon>
              </v-btn>
              <ExportarActividades
                  tipoActividad="pretratamiento">
              </ExportarActividades>
            </template>
          </v-dialog>
          <!-- Termina dialog de botones de agregar y recargar -->
        </v-toolbar>
        <v-text-field
            v-model="search"
            label="Búsqueda"
            class="mx-4">
        </v-text-field>
      </template>
      <template
          v-slot:item.actions="{ item }">
        <!-- Formulario para listar los productos de una actividad de caldera -->
        <DetalleListadoProductosActividadPretratamiento
          :idActividadPretratamiento="item.idListadoActividadPretratamiento">
        </DetalleListadoProductosActividadPretratamiento>
        <!-- Formulario para listar los productos de una actividad de caldera -->
      </template>
    </v-data-table>
    <!-- Termina la tabla de actividades de pretratamiento -->
  </div>
</template>
<script>

import axios from "axios";

export default {
name: "TablaActividadesPretratamiento",
  data: function () {
    return {
      search: '',
      encabezadosTabla: [{
          text: 'Código de actividad',
          align: 'center',
          sortable: false,
          value: 'idListadoActividadPretratamiento',
        },
        {
          text: 'Nombre de actividad',
          align: 'center',
          value: 'NombreActividad'
        },
        {
          text: 'Nombre de area en pretratamiento',
          align: 'center',
          value: 'NombreAreaPretratamiento'
        },
        {
          text: 'Creado por',
          align: 'center',
          value: 'CreadoPor'
        },
        {
          text: 'Realizado por',
          align: 'center',
          value: 'RealizadoPor'
        },
        {
          text: 'Estado',
          align: 'center',
          value: 'NombreEstado'
        },
        {
          text: 'Fecha y hora de creación',
          align: 'center',
          value: 'FechaCreacionActividad'
        },
        {
          text: 'Fecha y hora de cierre',
          align: 'center',
          value: 'FechaCreacionActividad'
        },
        {
          text: 'Acciones',
          align: 'center',
          value: 'actions',
          sortable: false
        },
      ],
      datosTabla: [],
    }
  },
  created() {
    this.inicializar();
  },
  methods:{
    inicializar: function (){
      return new Promise((resolve, reject) => {
        axios.get('/api/listadoactividadesgeneralpretratamiento ')
            .then(response => {
              if (response.data.status == 200) {
                this.datosTabla = response.data.detalle
              } else if (response.data.status == 404) {
                console.log(response.data.errores)
              }
              resolve(response)
            })
            .catch(error => {
              //console.log(error)

              reject(error)
            })
      })
    },
  },
}
</script>

<style scoped>

</style>