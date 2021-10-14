<template>
  <v-card
    flat>
    <v-card-title>
      <h4>
        Torre de enfriamiento
      </h4>
      <v-spacer></v-spacer>
      <v-dialog>
        <template
            v-slot:activator="{ on, attrs }">
          <AgregarActividadTorreEnfriamiento
              @inicializar="inicializar"
              v-if="verificarPermisos('agregarActividad')">
          </AgregarActividadTorreEnfriamiento>
          <v-btn
              text
              x-small
              @click="inicializar">
            <v-icon>
              mdi-reload
            </v-icon>
          </v-btn>
        </template>
      </v-dialog>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-row v-for="(item, index) in listadoActividadesTorreEnfriamiento" v-bind:key="index">
          <v-col>
            <v-card
                shaped
                elevation="10"
                :color="colorEstadoActividad(item.NombreEstado)">
              <v-card-title class="headline">{{ item.NombreActividad }}</v-card-title>
              <v-card-text>
                <div>Fecha de creación: {{ item.FechaCreacion }}</div>
                <div>Creado por: {{ item.CreadoPor }}</div>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <!-- Formulario para agregar productos actividad -->
                <DetalleActividadTorreEnfriamiento
                    :Actividad="item.idListadoActividadTorreEnfriamiento"
                    @inicializar="inicializar"
                    v-if="item.NombreEstado == 'Creado' ? verificarPermisos('agregarProductos') : false">
                </DetalleActividadTorreEnfriamiento>
                <!-- Formulario para agregar productos actividad -->
                <!-- Formulario para cambiar estado actividad -->
                <CambiarEstadoActividadTorreEnfriamiento
                    :ActividadCambiar="item.idListadoActividadTorreEnfriamiento"
                    @inicializar="inicializar"
                    v-if="item.NombreEstado == 'Creado' ? false : verificarPermisos('cambiarEstado')">
                </CambiarEstadoActividadTorreEnfriamiento>
                <!-- Formulario para cambiar estado actividad -->
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from "axios";

export default {
name: "ActividadesTorreEnfriamiento",
  data: () => ({
    listadoActividadesTorreEnfriamiento: [],
  }),
  created() {
    this.inicializar();
  },
  methods:{
    inicializar(){
      new Promise((resolve, reject) => {
        axios.get("/api/listadoactividadestorre")
            .then(response => {
              if (response.data.total != 0) {
                this.listadoActividadesTorreEnfriamiento = response.data.detalle
                //console.log(this.listadoActividadesCaldera)
              }
            })
            .catch(function (error) {
              console.log(error);
            })
      });
    },
    // Color por status de actividad
    // Color por status de actividad
    colorEstadoActividad(estado) {
      if (estado == 'Creado') {
        return 'creado';
      } else if (estado == 'Activo') {
        return 'activo';
      } else if (estado == 'En proceso') {
        return 'enproceso';
      } else if (estado == 'Cerrado') {
        return 'cerrado';
      }
    },
    verificarPermisos(nombreModulo){
      // Obtendremos el nombre del rol
      let nombreRol = localStorage.getItem('NombreRol');
      // Verificaremos si puede visualizarlo o no
      switch (nombreModulo){
        case 'agregarActividad':
        {
          if(nombreRol === 'Administrador' ||
              nombreRol === 'Gerente' ||
              nombreRol === 'Supervisor'){
            return true;
          }
          else{
            return false;
          }
        }
        case 'agregarProductos':
        {
          if(nombreRol === 'Administrador' ||
              nombreRol === 'Gerente' ||
              nombreRol === 'Supervisor'){
            return true;
          }
          else{
            return false;
          }
        }
        case 'cambiarEstado':
        {
          if(nombreRol === 'Administrador' ||
              nombreRol === 'Gerente' ||
              nombreRol === 'Supervisor' ||
              nombreRol === 'Auxiliar' ||
              nombreRol === 'Operador'){
            return true;
          }
          else{
            return false;
          }
        }
      }
    },
  },
}
</script>

<style scoped>

</style>