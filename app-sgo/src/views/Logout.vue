<template></template>

<script>

export default {
  created() {
    this.$store.dispatch("destroyToken").then(response => {
      //this.$router.push({ name: "login" });
      //bus.$emit('logged', 'User logged');
    });
  }
};
</script>