<template>
  <div>
    <v-row
        justify="center">
      <v-dialog
          v-model="dialog"
          persistent
          max-width="600px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
              text
              small
              v-bind="attrs"
              v-on="on">
            <v-icon>
              mdi-format-list-bulleted-type
            </v-icon>
          </v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">Detalle de actividad</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col
                    cols="12"
                    sm="6"
                    md="6"
                >
                  <v-text-field
                      label="Area"
                      v-model="actividadTorreEnfriamiento.NombreArea"
                      readonly
                  ></v-text-field>
                </v-col>
                <v-col
                    cols="12"
                    sm="6"
                    md="6"
                >
                  <v-text-field
                      label="Actividad a realizar"
                      v-model="actividadTorreEnfriamiento.NombreActividad"
                      readonly
                  ></v-text-field>
                </v-col>
                <v-col
                    cols="12"
                    sm="6"
                    md="6"
                >
                  <v-text-field
                      label="Creado por"
                      v-model="actividadTorreEnfriamiento.CreadoPor"
                      readonly
                  ></v-text-field>
                </v-col>
                <v-col
                    cols="12"
                    sm="6"
                    md="6"
                >
                  <v-text-field
                      label="Fecha de creación"
                      v-model="actividadTorreEnfriamiento.FechaCreacionActividad"
                      readonly
                  ></v-text-field>
                </v-col>
                <v-col cols="12">

                </v-col>
              </v-row>
              <v-row>
                <v-data-table
                    dense
                    caption="Listado de materiales"
                    no-data-text="No hay productos agregados"
                    :headers="encabezadosTabla"
                    :items="datosTabla"
                    sort-by="NombreActividad"
                >
                </v-data-table>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="error"
                text
                @click="dialog = false">
              Cerrar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "DetalleListadoProductosActividadTorreEnfriamiento",
  data: () => ({
    encabezadosTabla: [{
      text: 'Codigo producto',
      align: 'center',
      sortable: false,
      value: 'CodigoProducto',
    },
      {
        text: 'Nombre producto',
        align: 'start',
        value: 'NombreProducto'
      },
      {
        text: 'Cantidad producto',
        align: 'start',
        value: 'CantidadProducto'
      },
    ],
    dialog: false,
    idActividad: '',
    datosTabla: [],
    actividadTorreEnfriamiento: [],
  }),
  props:['idActividadTorreEnfriamiento'],
  created() {
    this.obtenerActividad();
  },
  methods:{
    obtenerActividad(){
      this.idActividad = this.idActividadTorreEnfriamiento;
      new Promise((resolve, reject) => {
        axios.get('/api/listadoactividadestorre/' + this.idActividad)
            .then(response => {
              if (response.data.status == 200) {
                this.actividadTorreEnfriamiento = response.data.detalle[0]
              } else if (response.data.status == 404) {
                console.log(response)
              }
              resolve(response)
            })
            .catch(error => {
              console.log(error)
              reject(error)
            })
      });
      new Promise((resolve, reject) => {
        axios.get('/api/listadomaterialactividadestorre/' + this.idActividad)
            .then(response => {
              if (response.data.status == 200) {
                if (response.data.detalle != "Registro no encontrado."){
                  this.datosTabla = response.data.detalle;
                }else{
                  this.datosTabla = [];
                }
              } else if (response.data.status == 404) {
                console.log(response);
              }
              resolve(response)
            })
            .catch(error => {
              console.log(error)
              reject(error)
            })
      })
    },
  },
}
</script>

<style scoped>

</style>