<template>
  <div>
    <!-- Attempting to render yarn-list directly.
         Ensure yarn-list is globally registered in service.js -->
    <yarn-list></yarn-list>
    
    <!-- You could also add a simple message to confirm ServiceApp itself is rendering -->
    <!-- <p>ServiceApp.vue is loaded.</p> -->
  </div>
</template>

<script setup>
// No need to import Yarn here if 'yarn-list' is globally registered
// and ServiceApp.vue is the root component for the app instance in service.js
</script>