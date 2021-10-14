<template>
  <div>
    <v-app-bar
        color="secondary"
        dense
        dark
        app
        v-if="isLogged">
      <v-app-bar-nav-icon
          @click="ocultarBarra()">
        <v-icon>
          {{ iconoBarra }}
        </v-icon>
      </v-app-bar-nav-icon>
      <v-toolbar-title>Gestor de infraestructuras informáticas</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn
          text
          :to="{name: 'logout'}"
          @click.prevent="singout">
        <v-icon>
          mdi-power</v-icon>
      </v-btn>
    </v-app-bar>
    <v-navigation-drawer
        color="primary"
        v-model="barraNavegacion"
        app
        floating
        :permanent="barraNavegacion"
        :mini-variant.sync="mini"
        dark
        v-if="isLogged">
      <v-list>
        <v-list-item class="px-2">
          <v-list-item-avatar>
            <v-img src="@/assets/images/nouser.png"></v-img>
          </v-list-item-avatar>
        </v-list-item>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="title">{{ NombreUsuario }}</v-list-item-title>
            <v-list-item-subtitle>{{ CorreoUsuario }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-divider></v-divider>
      <!-- Barra izquierda del menú -->
      <v-list>
        <v-list-item
        :to="{name: 'home'}"
        link>
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>

          <v-list-item-title>Home</v-list-item-title>
        </v-list-item>

        <!-- Muestra todos los menús -->
        <v-list-group
        v-for="item in items"
        :key="item.title"
        v-model="item.active"
        :prepend-icon="item.action"
        no-action
        color="accent"
      >
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title v-text="item.title"></v-list-item-title>
          </v-list-item-content>
        </template>

        <v-list-item
          v-for="child in item.items"
          :to="child.title"
          :key="child.title"
          link
        >
          <v-list-item-content>
            <v-list-item-title v-text="child.title"></v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-group>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>

import { bus } from '../main';

export default {
  data () {
    return {
      barraNavegacion: true,
      iconoBarra: 'mdi-minus',
      NombreUsuario: localStorage.getItem('NombreUsuario'),
      CorreoUsuario: localStorage.getItem('CorreoUsuario'),
      isLogged: this.checkIfIsLogged(),
      items: this.verificarPermisos(),
    }
  },
  created () {
    bus.$on('logged', () => {
      this.isLogged = this.checkIfIsLogged();
    });
    bus.$on('menu', () => {
      this.rutasMostrar = this.verificarPermisos();
    });
  },
  methods: {
    verificarPermisos(){
      let nombreRol = localStorage.getItem('NombreRol');
      if(nombreRol === 'Administrador'){
        //this.rutasMostrar = this.rutasAdministrador;
        var rutasAdmin = [
          {
            action: 'mdi-counter',
            active: false,
            items: [{ title: 'Agregados' },
                    { title: 'Prefijos' },
                    { title: 'Rangos' },
                    { title: 'Direcciones IP' },
                    { title: 'VLAN' },
            ],
            title: 'IPAM',
          },
          {
            action: 'mdi-domain',
            active: false,
            items: [
              { title: 'Sitios' },
              { title: 'Estantes (Rack)' },
              { title: 'Dispositivos' },
              { title: 'Cables' },
            ],
            title: 'DCIM',
          },
        ];
        return rutasAdmin;
      }
      else if(nombreRol === 'Invitado'){
        //this.rutasMostrar = this.rutasGerente;
        var rutasInvitado = [
          {
            action: 'mdi-counter',
            active: false,
            items: [{ title: 'Agregados' },
                    { title: 'Prefijos' },
                    { title: 'Rangos' },
                    { title: 'Direcciones IP' },
                    { title: 'VLAN' },
            ],
            title: 'IPAM',
          },
          {
            action: 'mdi-domain',
            active: false,
            items: [
              { title: 'Sitios' },
              { title: 'Estantes (Rack)' },
              { title: 'Dispositivos' },
              { title: 'Cables' },
            ],
            title: 'DCIM',
          },
        ];
        return rutasInvitado;
      }
    },
    singout() {
      //localStorage.remove('access_token')
      this.isLogged = this.checkIfIsLogged()
      //this.$router.push('/logout')
    },
    checkIfIsLogged() {
      let token = localStorage.getItem('access_token');
      if (token) {
        this.NombreUsuario = localStorage.getItem('NombreUsuario');
        this.CorreoUsuario = localStorage.getItem('CorreoUsuario');
        return true;
      } else {
        return true;
      }
    },
    ocultarBarra() {
      if (this.barraNavegacion == true) {
        this.barraNavegacion = false;
        this.iconoBarra = 'mdi-plus'
      } else if (this.barraNavegacion == false) {
        this.barraNavegacion = true;
        this.iconoBarra = 'mdi-minus'
      }
    },
  },
  computed: {
    mini: {
      get() {
        return this.$vuetify.breakpoint.mdAndDown || this.overwriteBreakpoint;
      },
      set(value) {
        this.overwriteBreakpoint = value;
      }
    },
  },
}
</script>
