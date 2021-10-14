<template>
  <v-card
    flat>
    <v-card-title>
      Pretratamiento
      <v-spacer></v-spacer>
      <v-dialog>
        <template
            v-slot:activator="{ on, attrs }">
          <AgregarActividadPretratamiento
              @inicializar="inicializarDatosPretratamiento"
              v-if="verificarPermisos('agregarActividad')">
          </AgregarActividadPretratamiento>
          <v-btn
              text
              x-small
              @click="inicializarDatosPretratamiento">
            <v-icon>
              mdi-reload
            </v-icon>
          </v-btn>
        </template>
      </v-dialog>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-row v-for="(itemPretratamiento, indexPretratamiento) in listadoActividadesPretratamiento" v-bind:key="indexPretratamiento">
          <v-col>
            <v-card
                shaped
                elevation="10"
                :color="colorEstadoActividad(itemPretratamiento.NombreEstado)">
              <v-card-title class="headline">{{ itemPretratamiento.NombreActividad }}</v-card-title>
              <v-card-text>
                <div>Area: {{ itemPretratamiento.NombreAreaPretratamiento }}</div>
                <div>Fecha de creación: {{ itemPretratamiento.FechaCreacionActividad }}</div>
                <div>Creado por: {{ itemPretratamiento.CreadoPor }}</div>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <!-- Formulario para agregar productos actividad -->
                <DetalleActividadPretratamiento
                    :Actividad="itemPretratamiento.idListadoActividadPretratamiento"
                    @inicializar="inicializarDatosPretratamiento"
                    v-if="itemPretratamiento.NombreEstado == 'Creado' ? verificarPermisos('agregarProductos') : false">
                </DetalleActividadPretratamiento>
                <!-- Formulario para agregar productos actividad -->
                <!-- Formulario para cambiar estado actividad -->
                <CambiarEstadoActividadPretratamiento
                    :ActividadCambiar="itemPretratamiento.idListadoActividadPretratamiento"
                    @inicializar="inicializarDatosPretratamiento"
                    v-if="itemPretratamiento.NombreEstado == 'Creado' ? false : verificarPermisos('cambiarEstado')">
                </CambiarEstadoActividadPretratamiento>
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
name: "ActividadesPretratamiento",
  data: () => ({
    listadoActividadesPretratamiento: [],
  }),
  created() {
    this.inicializarDatosPretratamiento();
  },
  methods:{
    inicializarDatosPretratamiento(){
      new Promise((resolve, reject) => {
        axios.get("/api/listadoactividadespretratamiento")
            .then(response => {
              if (response.data.total != 0) {
                this.listadoActividadesPretratamiento = response.data.detalle;
              }
            })
            .catch(function (error) {
              console.log(error);
            })
      });
    },
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