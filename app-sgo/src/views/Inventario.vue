<template>
  <!-- Contenido principal -->
  <div class="text-center">
    <!-- Snackbar de notificaciones -->
    <v-snackbar
      v-model="snackbar"
      :timeout="timeout"
      color="success">
      {{ textoSnackbar }}
      <template v-slot:action="{ attrs }">
        <v-btn
          color="success"
          text
          v-bind="attrs"
          @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <!-- Termina Snackbar de notificaciones -->

    <!-- Tabla de usuarios -->
    <v-data-table
        dense
        :headers="headers"
        :items="datosTabla"
        :items-per-page="10"
        sort-by="NombreUsuario"
        class="elevation-1">
      <template
        v-slot:top>
        <v-toolbar
          flat
          color="white">
          <v-toolbar-title>
            Inventario productos
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
              <ExportarAExcel
                  nombreArchivo="actividadescaldera"
                  :datosTabla="datosTabla">
              </ExportarAExcel>
            </template>
          </v-dialog>
          <!-- Termina dialog de botones de agregar y recargar -->

          <!-- Cuadro de edición de inventario -->
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
                  <v-container>
                    <v-row>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-autocomplete
                          :items="datosProductos"
                          :item-text="NombreCodigoProducto"
                          item-value='idProducto'
                          v-model="editedItem.idProducto"
                          :disabled="editarProducto"
                          label="Producto"
                          :rules="[rules.required]">
                        </v-autocomplete>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="editedItem.CantidadExistencia"
                          label="Cantidad en existencia"
                          :disabled="editarProducto"
                          :rules="[rules.required]">
                        </v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="editedItem.CantidadMinima"
                          label="Cantidad mínima"
                          :rules="[rules.required]">
                        </v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="editedItem.CantidadMaxima"
                          label="Cantidad maxima"
                          :rules="[rules.required]">
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
                  @click="guardarInformacion">
                  Guardar
                </v-btn>
              </v-card-actions>
            </v-card>
            <!-- Termina cuadro de edición de inventario -->
          </v-dialog>
          <!-- Cuadro para modificar minimos y maximos -->
          <v-dialog
            v-model="dialogModificarMinimosMaximos"
            max-width="500px">
            <v-card>
              <v-card-title>
                <span
                  class="headline">
                  Modificar cantidades mínimas y máximas
                </span>
              </v-card-title>
              <v-card-text>
                <v-form>
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
                  <v-container>
                    <v-row>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="editedItem.CodigoProducto"
                          :disabled="editarProducto"
                          label="Producto"
                          :rules="[rules.required]">
                        </v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="editedItem.TotalExistencia"
                          label="Cantidad en existencia"
                          :disabled="editarProducto"
                          :rules="[rules.required]">
                        </v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="editedItem.CantidadMinima"
                          label="Cantidad mínima"
                          :rules="[rules.required]">
                        </v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="editedItem.CantidadMaxima"
                          label="Cantidad maxima"
                          :rules="[rules.required]">
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
                  @click="cerrarDialogModificarMinimosMaximos">
                  Cancelar
                </v-btn>
                <v-btn
                  color="primary"
                  text
                  @click="guardarInformacion()">
                  Guardar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <!-- Termina cuadro para editar minimos y maximos -->

          <!-- Cuadro de agregar cantidad de productos -->
          <v-dialog
            v-model="dialogAgregarCantidadProducto"
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
                  <v-container>
                    <v-row>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="cantidadProductoAgregar.CodigoProducto"
                          :disabled="editarProducto"
                          label="Producto"
                          :rules="[rules.required]">
                        </v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="6">
                        <v-text-field
                          v-model="cantidadProductoAgregar.cantidadAgregar"
                          label="Cantidad a agregar"
                          :rules="[rules.required]">
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
                  @click="cerrarDialogAgregarCantidad">
                  Cancelar
                </v-btn>
                <v-btn
                  color="primary"
                  text
                  @click="agregarCantidadProductoInventario">
                  Guardar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <!-- Termina cuadro de agregar cantidad de productos -->
        </v-toolbar>
      </template>
      <template
        v-slot:item.actions="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="agregarProductoInventario(item, item.idInventario)">
          mdi-plus
        </v-icon>
        <v-icon
          small
          class="mr-2"
          @click="editarMinimosMaximosProducto(item, item.idInventario)">
          mdi-pencil
        </v-icon>
      </template>
    </v-data-table>
    <!-- Termina tabla de usuarios -->
  </div>
</template>

<script>
  import axios from "axios";

  export default {
    name: 'Inventario',
    data: () => ({
      snackbar: false,
      textoSnackbar: '',
      verContrasenia: false,
      timeout: 3000,
      toggle_exclusive: undefined,
      dialog: false,
      dialogAgregarCantidadProducto: false,
      dialogModificarMinimosMaximos: false,
      alertaErrores: false,
      headers: [{
          text: 'Código de producto',
          align: 'start',
          sortable: false,
          value: 'CodigoProducto',
        },
        {
          text: 'idProducto',
          value: 'idProducto'
        },
        {
          text: 'Producto',
          value: 'NombreProducto'
        },
        {
          text: 'Total en Existencia',
          value: 'TotalExistencia'
        },
        {
          text: 'Cantidad minima',
          value: 'CantidadMinima'
        },
        {
          text: 'Cantidad maxima',
          value: 'CantidadMaxima'
        },
        {
          text: 'Producto flotante',
          value: 'TotalFlotante'
        },
        {
          text: 'Actions',
          value: 'actions',
          sortable: false
        },
      ],
      rules: {
        required: value => !!value || 'Campo requerido.',
        min: v => v.length >= 8 || '8 caracteres como mínimo',
        counter: value => value.length <= 20 || 'Max 20 characters',
      },
      listadoErrores: [],
      datosTabla: [],
      datosProductos: [],
      editarProducto: false,
      select: [],
      editedIndex: -1,
      editedItem: {
        idProducto: '',
        CodigoProducto: '',
        TotalExistencia: '',
        CantidadMinima: '',
        CantidadMaxima: '',
        RegistradoPor: localStorage.getItem('idUsuario'),
      },
      defaultItem: {
        idProducto: '',
        CodigoProducto: '',
        TotalExistencia: '',
        CantidadMinima: '',
        CantidadMaxima: '',
        RegistradoPor: localStorage.getItem('idUsuario'),
      },
      modificarMinimosMaximos: {
        idProducto: '',
        cantidadAgregar: '',
        RegistradoPor: localStorage.getItem('idUsuario'),
      },
      deafultmodificarMinimosMaximos: {
        idProducto: '',
        cantidadAgregar: '',
        RegistradoPor: localStorage.getItem('idUsuario'),
      },
      cantidadProductoAgregar: {
        idProducto: '',
        cantidadAgregar: '',
        RegistradoPor: localStorage.getItem('idUsuario'),
      },
      defaultCantidadProductoAgregar: {
        idProducto: '',
        cantidadAgregar: '',
        RegistradoPor: localStorage.getItem('idUsuario'),
      },
    }),

    computed: {
      formTitle() {
        return this.editedIndex === -1 ? 'Registro de producto al inventario' : 'Agregar productos'
      },
    },

    watch: {
      dialog(val) {
        val || this.cerrarDialogAgregarCantidad() || this.cerrarDialogRegistro()
      },
    },

    created() {
      axios.defaults.headers.common['Authorization'] = localStorage.getItem('access_token');
      this.initialize()
    },

    methods: {
      initialize() {
        new Promise((resolve, reject) => {
          axios.get("/api/inventario")
            .then(response => {
              if (response.data.total != 0) {
                this.datosTabla = response.data.detalle
              }
            })
            .catch(function (error) {
              console.log(error);
            })
        });

        new Promise((resolve, reject) => {
          axios
            .get("/api/productonoinventariado")
            .then(response => {
              if (response.data.total != 0) {
                this.datosProductos = response.data.detalle
              }
            })
            .catch(function (error) {
              console.log(error);
            })
        });
      },

      // Concatenando el codigo con el nombre
      NombreCodigoProducto: item => item.CodigoProducto + ' - ' + item.NombreProducto,

      editarMinimosMaximosProducto(item, idInventario) {
        this.idInventarioEditar = idInventario
        this.editedIndex = this.datosTabla.indexOf(item)
        this.editedItem = Object.assign({}, item)
        if (this.editedIndex > -1) {
          this.editarProducto = true
        } else {
          this.editarProducto = false
        }
        this.dialogModificarMinimosMaximos = true
      },

      agregarProductoInventario(item, idInventario) {
        this.idInventarioAgregar = idInventario
        this.editedIndex = this.datosTabla.indexOf(item)
        this.cantidadProductoAgregar = Object.assign({}, item)
        if (this.editedIndex > -1) {
          this.editarProducto = true
        } else {
          this.editarProducto = false
        }
        this.dialogAgregarCantidadProducto = true
      },

      agregarCantidadProductoInventario() {
        // Pasamos el usuario
        this.cantidadProductoAgregar.RegistradoPor = localStorage.getItem('idUsuario');
        axios
          .post("/api/agregaracantidadinventario", this.cantidadProductoAgregar)
          .then(response => {
            if (response.data.status == 200) {
              // Mostramos la confirmación
              this.textoSnackbar = 'Cantidad agregada exitosamente'
              this.snackbar = !this.snackbar
              this.cerrarDialogAgregarCantidad()
            } else if (response.data.status == 404) {
              //console.log("error")
              this.listadoErrores = response.data.errores
              this.alertaErrores = true
            }
          })
          .catch(function (error) {
            console.log(error);
          })
        this.initialize();
      },

      guardarInformacion() {
        // Si el valor del índice de edición es mayor al que se está editando entonces
        if (this.editedIndex > -1) {
          axios
            .put("/api/inventario/" + this.editedItem.idProducto, this.editedItem)
            .then(response => {
              if (response.data.status == 200) {
                //console.log("exito")
                // Mostramos la confirmación
                this.textoSnackbar = 'Producto editado exitosamente'
                this.snackbar = !this.snackbar
                this.cerrarDialogRegistro()
                this.cerrarDialogModificarMinimosMaximos()
              } else if (response.data.status == 404) {
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
            .post("/api/inventario", this.editedItem)
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

      cerrarDialogAgregarCantidad() {
        this.alertaErrores = false
        this.dialogAgregarCantidadProducto = false
        this.dialog = false
        this.$nextTick(() => {
          this.cantidadProductoAgregar = Object.assign({}, this.defaultCantidadProductoAgregar)
        })
      },

      cerrarDialogModificarMinimosMaximos() {
        this.alertaErrores = false
        this.editarProducto = false
        this.dialog = false
        this.dialogModificarMinimosMaximos = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.idProductoEditar = 0
        })
      },

      cerrarDialogRegistro() {
        this.alertaErrores = false
        this.editarProducto = false
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