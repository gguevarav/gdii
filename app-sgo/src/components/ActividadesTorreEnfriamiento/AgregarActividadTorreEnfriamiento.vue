<template>
  <div>
    <v-dialog>
      <template
          v-slot:activator="{ on, attrs }">
        <v-btn
            text
            x-small
            @click="dialog = true">
          <v-icon>
            mdi-plus
          </v-icon>
        </v-btn>
      </template>
    </v-dialog>
    <!-- Notificación -->
    <v-snackbar
        v-model="activarNotificacion"
        :timeout="timeout"
        :color="colorNotificacion">
      {{ textoMostrarNotificacion }}
      <template
          v-slot:action="{ attrs }">
        <v-btn
            color="secondary"
            text
            v-bind="attrs"
            @click="activarNotificacion = !activarNotificacion">
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <!-- Notificación -->
    <!-- Formulario para agregar nuevas actividades -->
    <v-dialog
        v-model="dialog"
        max-width="500px">
      <v-card>
        <v-card-title>
          <span
              class="headline">
            Crear una nueva actividad
          </span>
        </v-card-title>
        <v-form
            ref="form"
            v-model="valid"
            lazy-validation
        >
          <v-card-text>
            <v-alert
                type="error"
                v-model="alertaErrores">
              Los registros contienen los siguientes errores:
              <li
                  v-for="value in listadoErrores"
                  v-bind:key>
                {{ value }}
              </li>
            </v-alert>

            <v-select
                :items="listadoNombreActividad"
                item-text='NombreActividad'
                item-value='idNombreActividad'
                v-model="actividadTorreEnfriamiento.idNombreActividad"
                label="Actividad a realizar"
                :rules="[rules.required]"
                required>
            </v-select>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="error"
                text
                @click="cerrarDialog">
              Cancelar
            </v-btn>
            <v-btn
                color="primary"
                text
                @click="agregarActividad">
              Guardar
            </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
  </div>
  <!-- Termina cuadro de agregar cantidad de productos -->
</template>

<script>
import axios from "axios";

export default {
  name: "AgregarActividadTorreEnfriamiento",
  data: () => ({
    dialog: false,
    activarNotificacion: false,
    timeout: 3000,
    colorNotificacion: 'success',
    textoMostrarNotificacion: '',
    toggle_exclusive: undefined,
    valid: true,
    alertaErrores: false,
    listadoErrores: [],
    actividadTorreEnfriamiento:{
      idArea: 3,
      idNombreActividad: '',
      EstadoActividad: 1,
      CreadoPor: localStorage.getItem('idUsuario'),
      RealizadoPor: localStorage.getItem('idUsuario'),
    },
    actividadTorreEnfriamientoVacio:{
      idArea: 3,
      idNombreActividad: '',
      EstadoActividad: 1,
      CreadoPor: localStorage.getItem('idUsuario'),
      RealizadoPor: localStorage.getItem('idUsuario'),
    },
    listadoNombreActividad: [],
    rules: {
      required: value => !!value || 'Campo requerido.',
      min: v => v.length >= 8 || '8 caracteres como mínimo',
      counter: value => value.length <= 20 || 'Max 20 characters',
    },
  }),
  watch: {
    dialog(val) {
      val || this.cerrarDialog()
    },
  },
  created() {
    this.inicializar();
  },
  methods:{
    inicializar(){
      // Obtener nombre de actividades
      new Promise((resolve, reject) => {
        axios.get("/api/nombreactividades")
            .then(response => {
              if (response.data.total != 0) {
                this.listadoNombreActividad = response.data.detalle
              }
            })
            .catch(function (error) {
              console.log(error);
            })
      });
    },
    agregarActividad () {
      this.$refs.form.validate()
      new Promise((resolve, reject) => {
        axios.post("/api/listadoactividadestorre", this.actividadTorreEnfriamiento)
            .then(response => {
              //console.log(this.actividadCaldera)
              if (response.data.status == 200) {
                //console.log("exito")
                // Mostramos la confirmación
                this.textoMostrarNotificacion = 'Actividad registrada exitosamente';
                this.colorNotificacion = 'success';
                this.activarNotificacion = !this.activarNotificacion
                this.cerrarDialog()
                this.$emit("inicializar");
              } else if (response.data.status == 404) {
                //console.log("error")
                this.listadoErrores = response.data.errores
                this.alertaErrores = true
              }
            })
            .catch(function (error) {
              console.log(error);
            })
      });
    },
    cerrarDialog(){
      this.$refs.form.resetValidation()
      //this.activarNotificacion = false,
      this.alertaErrores = false
      this.dialogAgregarCantidadProducto = false
      this.dialog = false
      this.$nextTick(() => {
        this.actividadTorreEnfriamiento = Object.assign({}, this.actividadTorreEnfriamientoVacio)
      })
    },
  },
}
</script>

<style scoped>

</style>