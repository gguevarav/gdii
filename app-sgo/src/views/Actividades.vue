<template>
  <!-- Contenido principal -->
  <v-row>
    <v-col
        cols="12"
        md="4"
        sm="12">
      <ActividadesCaldera>
      </ActividadesCaldera>
    </v-col>
    <v-col
        cols="12"
        md="4"
        sm="12">
      <ActividadesPretratamiento>
      </ActividadesPretratamiento>
    </v-col>
    <v-col
        cols="12"
        md="4"
        sm="12">
      <ActividadesTorreEnfriamiento>
      </ActividadesTorreEnfriamiento>
    </v-col>
  </v-row>
</template>

<script>
  import axios from "axios";

  export default {
    name: "Actividades",
    data: () => ({
    }),
  }
</script>