<template>
  <div>

    <v-row justify="center">
      <v-dialog
          v-model="dialog"
          persistent
          max-width="600px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
              text
              v-bind="attrs"
              v-on="on"
              small
          >
            Seguimiento
          </v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span
                class="headline">
              Cambio de estado de actividad
            </span>
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
                      disabled
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
                      disabled
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
                      disabled
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
                      disabled
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-select
                      :items="datosEstadoUsuario"
                      item-text='NombreEstadoUsuario'
                      item-value='idEstadoUsuario'
                      v-model="actividadTorreEnfriamiento.idEstado"
                      label="Estado">
                  </v-select>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="error"
                text
                @click="dialog = false"
            >
              Cancelar
            </v-btn>
            <v-btn
                color="primary"
                text
                @click="cambiarEstadoActividad"
            >
              Cambiar estado
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
name: "DetalleActividadTorreEnfriamiento",
  data: () => ({
    valid: true,
    dialog: false,
    idActividad: '',
    alertaErrores: false,
    listadoErrores: [],
    actividadTorreEnfriamiento: [],
    datosEstadoUsuario: [{
      idEstadoUsuario: 4,
      NombreEstadoUsuario: 'En proceso'
      },
      {
        idEstadoUsuario: 5,
        NombreEstadoUsuario: 'Cerrado'
      },
      {
        idEstadoUsuario: 6,
        NombreEstadoUsuario: 'Cancelado'
      }
    ],
    actividadTorreEnfriamientoVacio:{
      idArea: '',
      idNombreActividad: '',
      EstadoActividad: '',
    },
  }),
  created() {
    this.obtenerActividadCerrar()
  },
  props:['ActividadCambiar'],
  methods:{
    obtenerActividadCerrar(){
      this.idActividad = this.ActividadCambiar
      return new Promise((resolve, reject) => {
        axios.get('/api/listadoactividadestorre/' + this.idActividad)
            .then(response => {
              if (response.data.status == 200) {
                this.actividadTorreEnfriamiento = response.data.detalle[0]
              } else if (response.data.status == 404) {
                console.log("error")
              }
              resolve(response)
            })
            .catch(error => {
              console.log(error)
              reject(error)
            })
      })
    },
    cambiarEstadoActividad(){
      // Debemos cerrar la actividad para que no aparezca en el tablero
      axios.post('/api/cambiarestadoactividadtorre/' + this.idActividad,
              {
                EstadoActividad: this.actividadTorreEnfriamiento.idEstado,
                RealizadoPor: localStorage.getItem('idUsuario'),
              })
          .then(response => {
            if (response.data.status == 200) {
              this.dialog = false;
              this.actividadTorreEnfriamiento = Object.assign({}, this.actividadTorreEnfriamientoVacio);
              this.$emit("inicializar");
            } else if (response.data.status == 404) {
              this.listadoErrores = response.data.errores
              this.alertaErrores = true
            }
          })
          .catch(error => {
            console.log(error)
          })
    },
    cerrarDialog(){
      this.dialog = false;
    },
  },
}
</script>

<style scoped>

</style>