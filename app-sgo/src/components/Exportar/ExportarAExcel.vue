<template>
  <v-btn
      text
      small
      class="mb-2"
      @click="exportExcel">exportar a excel
    <v-icon>
      mdi-download
    </v-icon>
  </v-btn>
</template>

<script>
import XLSX from 'xlsx'

export default {
  name: "ExportarAExcel",

  data() {
    return {
      dataToExport: [],
      nombreParaGuardar: '',
    }
  },
  props:['datosTabla', 'nombreArchivo'],
  methods: {
    exportExcel: function () {
      this.dataToExport = this.datosTabla;
      this.nombreParaGuardar = this.nombreArchivo;
      let data = XLSX.utils.json_to_sheet(this.dataToExport)
      const workbook = XLSX.utils.book_new()
      const filename = this.nombreParaGuardar;
      XLSX.utils.book_append_sheet(workbook, data, filename)
      XLSX.writeFile(workbook, `${filename}.xlsx`)
    }
  },
}
</script>

<style scoped>

</style>