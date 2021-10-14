<template>
  <v-app>
    <!-- Barra de navegación -->
    <BarraNavegacion></BarraNavegacion>
    <v-main>
      <v-container>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>

export default {
  name: 'App',

};
</script>
