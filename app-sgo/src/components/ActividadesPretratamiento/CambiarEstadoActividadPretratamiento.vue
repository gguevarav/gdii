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
                    md="2"
                >
                  <v-text-field
                      label="Area"
                      v-model="actividadPretratamiento.NombreArea"
                      disabled
                  ></v-text-field>
                </v-col>
                <v-col
                    cols="12"
                    sm="6"
                    md="3"
                >
                  <v-text-field
                      label="Area pretratamiento"
                      v-model="actividadPretratamiento.NombreAreaPretratamiento"
                      disabled
                  ></v-text-field>
                </v-col>
                <v-col
                    cols="12"
                    sm="6"
                    md="5"
                >
                  <v-text-field
                      label="Actividad a realizar"
                      v-model="actividadPretratamiento.NombreActividad"
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
                      v-model="actividadPretratamiento.CreadoPor"
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
                      v-model="actividadPretratamiento.FechaCreacionActividad"
                      disabled
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-select
                      :items="datosEstadoUsuario"
                      item-text='NombreEstadoUsuario'
                      item-value='idEstadoUsuario'
                      v-model="actividadPretratamiento.idEstado"
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
name: "DetalleActividadPretratamiento",
  data: () => ({
    valid: true,
    dialog: false,
    idActividad: '',
    alertaErrores: false,
    listadoErrores: [],
    actividadPretratamiento: [],
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
    actividadPretratamientoVacio:{
      idArea: '',
      idAreaPretratamiento: '',
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
        axios.get('/api/listadoactividadespretratamiento/' + this.idActividad)
            .then(response => {
              if (response.data.status == 200) {
                this.actividadPretratamiento = response.data.detalle[0]
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
      axios.post('/api/cambiarestadoactividadpretratamiento/' + this.idActividad,
              {
                EstadoActividad: this.actividadPretratamiento.idEstado,
                RealizadoPor: localStorage.getItem('idUsuario'),
              })
          .then(response => {
            if (response.data.status == 200) {
              this.dialog = false;
              this.actividadPretratamiento = Object.assign({}, this.actividadPretratamientoVacio);
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