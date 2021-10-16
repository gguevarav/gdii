<template>
  <!-- Contenido principal -->
  <div class="text-center">
    <!-- Snackbar de notificaciones -->
    <v-snackbar
      v-model="snackbar"
      :timeout="timeout">

      {{ textoSnackbar }}

      <template
        v-slot:action="{ attrs }">
          <v-btn
            color="success"
            text
            v-bind="attrs"
            @click="snackbar = false">
              Close
          </v-btn>
      </template>
    </v-snackbar>
    <!-- Termina snackbar de notificaciones -->

    <!-- Tabla de Agregados -->
    <v-data-table
      dense
      :headers="headers"
      :items="datosTabla"
      :items-per-page="10"
      sort-by="Agregado"
      class="elevation-1">
      <template
        v-slot:top>
        <v-toolbar
          flat
          color="white">
          <v-toolbar-title>
            Agregados
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <!-- Dialog de botones de agregar y recargar -->
          <v-dialog>
            <template
                v-slot:activator="{ on, attrs }">
                  <v-btn
                    text
                    class="mb-2"
                    @click="dialog = true">
                      <v-icon>
                        mdi-plus
                      </v-icon>
                </v-btn>
                <v-btn
                  text
                  class="mb-2"
                  @click="initialize">
                    <v-icon>
                      mdi-reload
                    </v-icon>
                </v-btn>
              </template>
          </v-dialog>
          <!-- Termina dialog de botones de agregar y recargar -->

          <!-- Dialog de registro de nuevo agregado -->
          <v-dialog
            v-model="dialog"
            max-width="500px">
            <v-card>
              <v-card-title>
                <span
                  class="headline">
                    {{ formTitle }}
                </span>
              </v-card-title>

              <v-card-text>
                <v-form>
                  <v-container>
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
                    <v-row
                      align="center"
                      justify="center">
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="editedItem.prefix"
                          label="Agregado"
                          :rules="[rules.required]"
                          required>
                        </v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-btn-toggle
                          v-model="toggle_exclusive"
                          group
                          multiple>
                            <v-select
                              :items="listadoRIRS"
                              item-text='display'
                              item-value='id'
                              v-model="editedItem.rir"
                              label="Nombre RIR"
                              :rules="[rules.required]"
                              required>
                            </v-select>
                        </v-btn-toggle>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="12"
                        md="12">
                        <v-text-field
                          v-model="editedItem.description"
                          label="Descripción"
                          :rules="[rules.required]"
                          required>
                        </v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-form>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="error"
                  text
                  @click="cerrarDialogRegistro">
                    Cancelar
                </v-btn>
                <v-btn
                  color="primary"
                  text
                  @click="registrarInformacion">
                    Guardar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <!-- Termina dialog de registro de nuevo agregado -->
        </v-toolbar>
      </template>
      <template
        v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="editarItem(item, item.id)">
              mdi-pencil
          </v-icon>
          <v-icon
            small
            disabled
            @click="eliminarProducto(item.id)">
              mdi-delete
          </v-icon>
      </template>
    </v-data-table>
    <!-- Termina la tabla de Agregados -->
  </div>
</template>

<script>
  import axios from "axios";

  export default {
    data: () => ({
      snackbar: false,
      textoSnackbar: '',
      timeout: 3000,
      toggle_exclusive: undefined,
      dialog: false,
      alertaErrores: false,
      headers: [{
          text: 'identificador',
          align: 'start',
          sortable: false,
          value: 'id',
        },
        {
          text: 'Agregado',
          value: 'display'
        },
        {
          text: 'RIR',
          value: 'rir.display'
        },
        {
          text: 'Descripción',
          value: 'description'
        },
        {
          text: 'Fecha de creación',
          value: 'created'
        },
        {
          text: 'Acciones',
          value: 'actions',
          sortable: false
        },
      ],
      rules: {
        required: value => !!value || 'Campo requerido.',
        min: v => v.length >= 8 || '8 caracteres como mínimo',
        counter: value => value.length <= 20 || 'Max 20 characters',
        email: value => {
          const pattern =
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          return pattern.test(value) || 'Correo inválido'
        },
      },
      listadoErrores: [],
      datosTabla: [],
      select: [],
      listadoRIRS: [],
      editedIndex: -1,
      idItemEditar: 0,
      editarCodigo: false,
      editedItem: {
        prefix: '',
        rir: '',
        description: '',
      },
      defaultItem: {
        prefix: '',
        rir: '',
        description: '',
      },
    }),

    computed: {
      formTitle() {
        return this.editedIndex === -1 ? 'Nuevo agregado' : 'Editar agregado'
      },
    },

    watch: {
      dialog(val) {
        val || this.cerrarDialogRegistro()
      },
    },

    created() {
      axios.defaults.baseURL ="https://143.198.179.90:8081"
      axios.defaults.headers.common['Content-Type'] = 'application/json';
      axios.defaults.headers.common['Authorization'] = 'Token 04a4e8ce9cc634845b75680a9d20d32bfdb62612';
      this.initialize()
    },

    methods: {
      initialize() {
        axios
          .get("/api/ipam/aggregates/")
          .then(result => {
            if (result.data.count != 0) {
              this.datosTabla = result.data.results;
              //console.log(result.data);
              //console.log(this.datosTabla);
            }
          })
          .catch(function (error) {
            //console.log(error.request);
            //console.log(error.message);
            console.log(error.response.request._response);
          }),
          axios
          .get("/api/ipam/rirs/")
          .then(result => {
            if (result.data.count != 0) {
              this.listadoRIRS = result.data.results;
              //console.log(result.data);
              //console.log(this.datosTabla);
            }
          })
          .catch(function (error) {
            //console.log(error.request);
            //console.log(error.message);
            console.log(error.response.request._response);
          })
      },

      editarItem(item, id) {
        this.idItemEditar = id
        this.editedIndex = this.datosTabla.indexOf(item)
        this.editedItem = Object.assign({}, item)
        if (this.editedIndex > -1) {
          this.editarCodigo = true
        } else {
          this.editarCodigo = false
        }
        this.dialog = true
      },

      eliminarProducto(id) {
        confirm('¿Está seguro que desea eliminar este agregado?') && axios
          .delete("/api/ipam/aggregates/" + id)
          .then(function (restult) {
            //console.log(response);
          })
          .catch(function (error) {
            console.log(error);
          })
      },

      registrarInformacion() {
        // Si el valor del índice de edición es mayor al que se está editando entonces 
        if (this.editedIndex > -1) {
          axios
            .put("/api/ipam/aggregates/" + this.idItemEditar, this.editedItem)
            .then(result => {
              if(result.data.status == 200){
                //console.log(response);
                // Cerramos el cuadro de diálogo y mostraremos una notificación
                this.textoSnackbar = 'Agregado modificado exitosamente'
                this.snackbar = !this.snackbar
                this.cerrarDialogRegistro()
              }else if(response.data.status == 404){
                console.log("error")
                //this.listadoErrores = response.data.errores
                //this.alertaErrores = true
              }
            })
            .catch(function (error) {
              console.log(error);
            })
        } else {
          axios
            .post("/api/ipam/aggregates/", this.editedItem)
            .then(result => {
              if (result.data.status == 200) {
                //console.log("exito")
                // Mostramos la confirmación
                this.textoSnackbar = 'Agregado registrado exitosamente'
                this.snackbar = !this.snackbar
                this.cerrarDialogRegistro()
              } else if (response.data.status == 404) {
                console.log("error")
                //this.listadoErrores = response.data.errores
                //this.alertaErrores = true
              }
            })
            .catch(function (error) {
              console.log(error);
            })
        }
        this.initialize()
      },

      cerrarDialogRegistro() {
        this.alertaErrores = false
        this.editarCodigo = false
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.idItemEditar = 0
        })
      },
    },
  }
</script>