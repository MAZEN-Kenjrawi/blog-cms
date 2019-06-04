<template>
  <vuetable ref="vuetable"
    :api-url="endpoint"
    :fields="schema"
    data-path=""
    pagination-path=""
  ></vuetable>
</template>

<script>
import Vuetable from 'vuetable-2'

export default {
  props: ['endpoint', 'schema'],
  components: {
    Vuetable
  }
}
</script>