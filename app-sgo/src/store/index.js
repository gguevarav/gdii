import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import router from "@/router";
import {bus} from "@/main";

//axios.defaults.withCredentials = true;
axios.defaults.baseURL ="http://localhost:8000"

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token: localStorage.getItem('access_token') || null,
    datos: null,
    NombreUsuario: null,
    CorreoUsuario: null,
  },
  getters:{
    loggedIn(state){
      return state.token !== null
    }
  },
  mutations: {
    retrieveToken(state, token){
      state.token = token
    },
    destroyToken (state){
      state.token = null
    },
    obtenerInformacionUsuario(state, datos){
      state.datos = datos
    },
    almacenarInfoUsuario(state, infoUsuario){

    },
    colorEstadoActividad(state, estado){
      state.color = estado
    }
  },
  actions: {
    retrieveToken(context, credentials) {

      return new Promise((resolve, reject) => {
        axios.post('/api/auth/login', {
          email: credentials.username,
          password: credentials.password,
        })
          .then(response => {
            //console.log(response)
            const token = response.data.access_token
            localStorage.setItem('access_token', 'Bearer ' + token)
            //localStorage.setItem()
            context.commit('retrieveToken', token)
            //this.obtenerInformacionUsuario(credentials);
            resolve(response)
          })
          .catch(error => {
            //console.log(error)
            reject(error)
          })
      });

    },

    obtenerInformacionUsuario(context, datos){

      return new Promise((resolve, reject) => {
        // Almacenamos la info del usuario
        axios.get('/api/auth/user', '', {
          headers: { Authorization: context.state.token }
        })
            .then(response => {
              // Almacenaremos los datos del usuario en el localstorage
              //localStorage.setItem('NombreUsuario', response.data);
              console.log(response);
              resolve(response)
            })
            .catch(error => {
              console.log(error)
              console.log("error")
              reject(error)
            })
      });
    },

    destroyToken(context) {
      
      if (context.getters.loggedIn){
        
        return new Promise((resolve, reject) => {
          axios.post('/api/auth/logout', '', {
              headers: { Authorization: 'Bearer ' + context.state.token }
            })
            .then(response => {
              //console.log(response)
              localStorage.removeItem('access_token')
              localStorage.removeItem('idUsuario')
              localStorage.removeItem('NombreUsuario')
              localStorage.removeItem('CorreoUsuario')
              localStorage.removeItem('idRol')
              localStorage.removeItem('NombreRol')
              localStorage.clear();
              bus.$emit('logged', 'User logged');
              router.push({ name: "login" });
              context.commit('destroyToken')
  
              resolve(response)
            })
            .catch(error => {
              //console.log(error)
              localStorage.removeItem('access_token')
              localStorage.removeItem('idUsuario')
              localStorage.removeItem('NombreUsuario')
              localStorage.removeItem('CorreoUsuario')
              localStorage.removeItem('idRol')
              localStorage.removeItem('NombreRol')
              localStorage.clear();
              bus.$emit('logged', 'User logged');
              router.push({ name: "login" });
              context.commit('destroyToken')

              reject(error)
            })
        })
      }
    },

    // Color por status de actividad
    colorEstadoActividad(context, estado){
      var color = '';
      if(estado.NombreEstado == 'Activo'){
        var color = 'red';
      }else if(estado.NombreEstado == 'En proceso'){
        return 'yellow';
      }else if(estado.NombreEstado == 'Cerrado') {
        return 'green';
      }
      context.commit('colorEstadoActividad', color);
    }
  },
  modules: {
  }
})
