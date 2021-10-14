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
              Agregar productos
          </v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">Agregar productos</span>
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
                  <AgregarMaterialActividad
                      ref="agregarMaterial"
                      :Actividad="idActividad"
                      tipoActividad="pretratamiento"
                      @actualizar="actualizar"
                      @dialog="dialog=$event">
                  </AgregarMaterialActividad>
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
                @click="guardarDatos"
            >
              Guardar
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
  }),
  created() {
    this.obtenerActividadCerrar()
  },
  props:['Actividad'],
  methods:{
    obtenerActividadCerrar(){
      this.idActividad = this.Actividad
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
    guardarDatos(){
      // Gurdamos los datos, usamos el método del componenete hijo
      this.$refs.agregarMaterial.guardarDatosMateriales();
    },
    cerrarDialog(){
      this.dialog = false;
    },
    actualizar(){
      this.$emit("inicializar");
    },
  },
}
</script>

<style scoped>

</style>