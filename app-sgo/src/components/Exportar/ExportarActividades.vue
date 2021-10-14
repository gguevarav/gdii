<template>
  <v-row>
    <v-col
        cols="12"
        sm="6"
        md="6"
    >
      <v-menu
          v-model="menu2"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
              v-model="dateRangeText"
              label="Fechas a exportar"
              prepend-icon="mdi-calendar"
              readonly
              v-bind="attrs"
              v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker
            v-model="dates"
            @input="menu2 = true"
            range
        ></v-date-picker>
      </v-menu>
    </v-col>
    <v-col
        cols="12"
        sm="6"
        md="6"
    >
      <v-btn
          text
          small
          class="mb-2"
          @click="exportExcel">exportar a excel
        <v-icon>
          mdi-download
        </v-icon>
      </v-btn>
    </v-col>
  </v-row>
</template>

<script>
import XLSX from 'xlsx'
import axios from "axios";

export default {
  name: "ExportarAExcel",

  data() {
    return {
      dataToExport: [],
      nombreParaGuardar: '',
      dates: [],
      menu2: false,
    }
  },
  computed: {
    dateRangeText() {
      return this.dates.join(' ~ ')
    },
  },
  props:['tipoActividad'],
  methods: {
    exportExcel: function () {
      // Guardamos el tipo de actividad que estamos exportando
      let TipoActividad = this.tipoActividad;
      // La ruta de donde obtendremos los datos
      let ruta;
      // Nombre del archivo
      let nombreArchivo;
      // Verificar origen para determinar el nombre y la ruta a utilizar.
      if(TipoActividad === "caldera") {
        ruta = "listadoactividadesporfechacaldera";
        nombreArchivo = "actividadescaldera";
      }
        else if(TipoActividad === "pretratamiento"){
          ruta = "listadoactividadesporfechapretratamiento";
          nombreArchivo = "actividadespretratamiento";
        }
          else if(TipoActividad === "torreEnfriamiento"){
            ruta = "listadoactividadesporfechatorre";
            nombreArchivo = "actividadestorreenfriamiento";
          }
        // Primero obtendremos los datos
        return new Promise((resolve, reject) => {
          axios.post('/api/' + ruta,
              {
                FechaInicial: this.dates[0],
                FechaFinal: this.dates[1]
              })
              .then(response => {
                if (response.data.status == 200) {
                  if(response.data.total === 0){
                    alert("No hay datos para exportar");
                  }else{
                    this.nombreParaGuardar = nombreArchivo;
                    let data = XLSX.utils.json_to_sheet(response.data.detalle)
                    const workbook = XLSX.utils.book_new()
                    const filename = this.nombreParaGuardar;
                    XLSX.utils.book_append_sheet(workbook, data, filename)
                    XLSX.writeFile(workbook, `${filename}.xlsx`)
                  }
                } else if (response.data.status == 404) {
                  console.log(response.data.errores)
                }
                resolve(response)
              })
              .catch(error => {
                //console.log(error)

                reject(error)
              })
        })

    }
  },
}
</script>

<style scoped>

</style>