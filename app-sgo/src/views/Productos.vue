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

    <!-- Tabla de productos -->
    <v-data-table
      dense
      :headers="headers"
      :items="datosTabla"
      :items-per-page="10"
      sort-by="NombreProducto"
      class="elevation-1">
      <template
        v-slot:top>
        <v-toolbar
          flat
          color="white">
          <v-toolbar-title>
            Productos
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

          <!-- Dialog de registro de producto -->
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
                            v-model="editedItem.CodigoProducto"
                            :disabled="editarCodigo"
                            label="Código"
                            :rules="[rules.required]"
                            required>
                          </v-text-field>
                        </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                          <v-text-field
                            v-model="editedItem.NombreProducto"
                            label="Nombre producto"
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
                              :items="datosUnidadMedida"
                              item-text='NombreUnidadMedida'
                              item-value='idUnidadMedida'
                              v-model="editedItem.idUnidadMedida"
                              label="UnidadMedida"
                              :rules="[rules.required]"
                              required>
                            </v-select>
                            <v-btn
                              text
                              @click="dialogUnidadMedida = !dialogUnidadMedida">
                                <v-icon>
                                  mdi-plus
                                </v-icon>
                            </v-btn>
                        </v-btn-toggle>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                          <v-select
                            :items="datosEstadoProducto"
                            item-text='NombreEstadoProducto'
                            item-value='idEstadoProducto'
                            v-model="editedItem.EstadoProducto"
                            label="Estado"
                            :rules="[rules.required]"
                            required>
                          </v-select>
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
                  @click="guardarProducto">
                    Guardar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <!-- Termina dialog de registro de producto -->

          <!-- Dialog de registro de unidad de medida -->
          <v-dialog
            v-model="dialogUnidadMedida"
            max-width="500px">
              <v-card>
                <v-card-title>
                  <span
                    class="headline">
                      Nueva unidad de medida
                  </span>
                </v-card-title>

                <v-card-text>

                  <v-alert
                      type="error"
                      v-model="alertaErroresUM">
                        Los registros contienen los siguientes errores:
                        <li
                          v-for="value in listadoErroresUM"
                          v-bind:key>
                            {{ value }}
                        </li>
                    </v-alert>

                  <v-form>
                    <v-container>
                      <v-row
                        align="center"
                        justify="center">
                        <v-col
                          cols="12"
                          sm="6"
                          md="6">
                          <v-text-field
                            v-model="nuevoUM.NombreUnidadMedida"
                            label="Nombre"
                            :rules="[rules.required]"
                            required>
                          </v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="6">
                            <v-text-field
                              v-model="nuevoUM.AbreviacionUnidadMedida"
                              label="Abreviatura"
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
                    @click="cerrarDialogUM">
                      Cancelar
                  </v-btn>
                  <v-btn
                    color="primary"
                    text
                    @click="guardarUnidadMedida">
                      Guardar
                  </v-btn>
                </v-card-actions>
              </v-card>
          </v-dialog>
          <!-- Termina dialog de registro de unidad de medida -->
        </v-toolbar>
      </template>
      <template
        v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="editarProducto(item, item.idProducto)">
              mdi-pencil
          </v-icon>
          <v-icon
            small
            disabled
            @click="eliminarProducto(item.idProducto)">
              mdi-delete
          </v-icon>
      </template>
    </v-data-table>
    <!-- Termina la tabla de productos -->
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
      dialogUnidadMedida: false,
      alertaErrores: false,
      alertaErroresUM: false,
      headers: [{
          text: 'idProducto',
          align: 'start',
          sortable: false,
          value: 'idProducto',
        },
        {
          text: 'Código',
          value: 'CodigoProducto'
        },
        {
          text: 'Nombre del producto',
          value: 'NombreProducto'
        },
        {
          text: 'Unidad de medida',
          value: 'NombreUnidadMedida'
        },
        {
          text: 'Estado',
          value: 'NombreEstado'
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
      listadoErroresUM: [],
      datosTabla: [],
      select: [],
      datosUnidadMedida: [],
      datosEstadoProducto: [{
          idEstadoProducto: 2,
          NombreEstadoProducto: 'Activo'
        },
        {
          idEstadoProducto: 3,
          NombreEstadoProducto: 'Inactivo'
        }
      ],
      editedIndex: -1,
      idProductoEditar: 0,
      editarCodigo: false,
      editedItem: {
        CodigoProducto: '',
        NombreProducto: '',
        idUnidadMedida: '',
        EstadoProducto: '',
      },
      defaultItem: {
        CodigoProducto: '',
        NombreProducto: '',
        idUnidadMedida: '',
        EstadoProducto: '',
      },
      nuevoUM: {
        NombreUnidadMedida: '',
        AbreviacionUnidadMedida: '',
      },
      defaultNuevoUM: {
        NombreUnidadMedida: '',
        AbreviacionUnidadMedida: '',
      },
    }),

    computed: {
      formTitle() {
        return this.editedIndex === -1 ? 'Nuevo producto' : 'Editar producto'
      },
    },

    watch: {
      dialog(val) {
        val || this.cerrarDialogUM() || this.cerrarDialogRegistro()
      },
    },

    created() {
      axios.defaults.headers.common['Authorization'] = localStorage.getItem('access_token');
      this.initialize()
    },

    methods: {
      initialize() {
        axios
          .get("/api/productos")
          .then(response => {
            if (response.data.total != 0) {
              this.datosTabla = response.data.detalle
            }
          })
          .catch(function (error) {
            console.log(error);
          })
        axios
          .get("/api/unidadesmedida")
          .then(response => {
            if (response.data.total != 0) {
              this.datosUnidadMedida = response.data.detalle
            }
          })
          .catch(function (error) {
            console.log(error);
          })
      },

      editarProducto(item, idProducto) {
        this.idProductoEditar = idProducto
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
        confirm('¿Está seguro que desea eliminar este producto?') && axios
          .delete("/api/productos/" + id)
          .then(function (response) {
            //console.log(response);
          })
          .catch(function (error) {
            console.log(error);
          })
      },

      guardarProducto() {
        // Si el valor del índice de edición es mayor al que se está editando entonces 
        if (this.editedIndex > -1) {
          axios
            .put("/api/productos/" + this.idProductoEditar, this.editedItem)
            .then(response => {
              if(response.data.status == 200){
                //console.log(response);
                // Cerramos el cuadro de diálogo y mostraremos una notificación
                this.textoSnackbar = 'Producto editado exitosamente'
                this.snackbar = !this.snackbar
                this.cerrarDialogRegistro()
              }else if(response.data.status == 404){
                //console.log("error")
                this.listadoErrores = response.data.errores
                this.alertaErrores = true
              }
            })
            .catch(function (error) {
              console.log(error);
            })
        } else {
          axios
            .post("/api/productos", this.editedItem)
            .then(response => {
              if (response.data.status == 200) {
                //console.log("exito")
                // Mostramos la confirmación
                this.textoSnackbar = 'Producto agregado exitosamente'
                this.snackbar = !this.snackbar
                this.cerrarDialogRegistro()
              } else if (response.data.status == 404) {
                //console.log("error")
                this.listadoErrores = response.data.errores
                this.alertaErrores = true
              }
            })
            .catch(function (error) {
              console.log(error);
            })
        }
        this.initialize()
      },

      guardarUnidadMedida() {
        axios
          .post("/api/unidadesmedida",
            this.nuevoUM)
          .then(response => {
              if (response.data.status == 200) {
                //console.log("exito")
                // Mostramos la confirmación
                this.textoSnackbar = 'Unidad de medida agregada exitosamente'
                this.snackbar = !this.snackbar
                this.cerrarDialogUM()
              } else if (response.data.status == 404) {
                //console.log("error")
                this.listadoErroresUM = response.data.errores
                this.alertaErroresUM = true
              }
            })
          .catch(function (error) {
            console.log(error);
          })
        this.initialize()
      },

      cerrarDialogUM() {
        this.alertaErrores = false
        this.alertaErroresUM = false
        this.dialogUnidadMedida = false
        //this.dialog = false
        this.$nextTick(() => {
          this.nuevoUM = Object.assign({}, this.defaultNuevoUM)
        })
      },

      cerrarDialogRegistro() {
        this.alertaErrores = false
        this.alertaErroresUM = false
        this.dialogUnidadMedida = false
        this.editarCodigo = false
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.idProductoEditar = 0
        })
      },
    },
  }
</script>