<template>
  <v-card
    flat>
    <v-card-title>
      Caldera
      <v-spacer></v-spacer>
      <v-dialog>
        <template
            v-slot:activator="{ on, attrs }">
          <AgregarActividadCaldera
            @inicializar="inicializar"
            v-if="verificarPermisos('agregarActividad')">
          </AgregarActividadCaldera>
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
        <v-row v-for="(item, index) in  listadoActividadesCaldera" v-bind:key="index">
          <v-col>
            <v-card
                shaped
                elevation="10"
                :color="colorEstadoActividad(item.NombreEstado)">
              <v-card-title class="headline">{{ item.NombreActividad }}</v-card-title>
              <v-card-text>
                <div>Caldera: {{ item.NombreCaldera }}</div>
                <div>Area: {{ item.NombreAreaCaldera }}</div>
                <div>Fecha de creación: {{ item.FechaCreacionActividad }}</div>
                <div>Creado por: {{ item.CreadoPor }}</div>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <!-- Formulario para agregar productos actividad -->
                <DetalleActividadCaldera
                    :Actividad="item.idListadoActividadCaldera"
                    @inicializar="inicializar"
                    v-if="item.NombreEstado == 'Creado' ? verificarPermisos('agregarProductos') : false">
                </DetalleActividadCaldera>
                <!-- Formulario para agregar productos actividad -->
                <!-- Formulario para cambiar estado actividad -->
                <CambiarEstadoActividadCaldera
                    :ActividadCambiar="item.idListadoActividadCaldera"
                    @inicializar="inicializar"
                    v-if="item.NombreEstado == 'Creado' ? false : verificarPermisos('cambiarEstado')">
                </CambiarEstadoActividadCaldera>
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
name: "ActividadesCaldera",
  data: () => ({
    listadoActividadesCaldera: [],
  }),
  created(){
    this.inicializar();
  },
  methods: {
    inicializar(){
      new Promise((resolve, reject) => {
        axios.get("/api/listadoactividadescaldera")
            .then(response => {
              if (response.data.total != 0) {
                this.listadoActividadesCaldera = response.data.detalle
                //console.log(this.listadoActividadesCaldera)
              }
            })
            .catch(function (error) {
              console.log(error);
            })
      });
    },
    // Color por status de actividad
    colorEstadoActividad(estado){
      if(estado == 'Creado') {
        return 'creado';
      }else if(estado == 'Activo'){
        return 'activo';
      }else if(estado == 'En proceso'){
        return 'enproceso';
      }else if(estado == 'Cerrado') {
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