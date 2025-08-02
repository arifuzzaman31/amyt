<template>
    <select ref="selectElement" :multiple="multiple" :disabled="disabled"></select>
  </template>
  
  <script>
  import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
  import $ from 'jquery';
  
  export default {
    name: 'Select2Wrapper',
    props: {
      modelValue: {
        type: [String, Number, Array, Object, null],
        default: null
      },
      settings: {
        type: Object,
        default: () => ({})
      },
      disabled: {
        type: Boolean,
        default: false
      },
      multiple: {
        type: Boolean,
        default: false
      }
    },
    emits: ['update:modelValue', 'select', 'change', 'unselect'],
    setup(props, { emit }) {
      const selectElement = ref(null);
      let select2Instance = null;
  
      const initializeSelect2 = () => {
        if (!selectElement.value) return;
        
        // Merge default settings with props
        const defaultSettings = {
          placeholder: props.multiple ? 'Select options' : 'Select an option',
          allowClear: !props.multiple,
          width: '100%'
        };
        
        const mergedSettings = { ...defaultSettings, ...props.settings };
        
        try {
          // Initialize Select2
          select2Instance = $(selectElement.value).select2(mergedSettings);
          
          // Set initial value
          if (props.modelValue !== null && props.modelValue !== undefined) {
            $(selectElement.value).val(props.modelValue).trigger('change');
          }
          
          // Bind events
          $(selectElement.value).on('select2:select', handleSelect);
          $(selectElement.value).on('select2:unselect', handleUnselect);
          $(selectElement.value).on('change', handleChange);
        } catch (error) {
          console.error('Error initializing Select2:', error);
        }
      };
  
      const handleSelect = (event) => {
        const data = event.params.data;
        emit('select', data);
        
        if (props.multiple) {
          const currentValues = $(selectElement.value).val() || [];
          emit('update:modelValue', currentValues);
        } else {
          emit('update:modelValue', data.id);
        }
      };
  
      const handleUnselect = (event) => {
        const data = event.params.data;
        emit('unselect', data);
        
        if (props.multiple) {
          const currentValues = $(selectElement.value).val() || [];
          emit('update:modelValue', currentValues);
        } else {
          emit('update:modelValue', null);
        }
      };
  
      const handleChange = (event) => {
        const value = $(event.target).val();
        emit('change', value);
        
        if (props.multiple) {
          emit('update:modelValue', value);
        } else {
          emit('update:modelValue', value);
        }
      };
  
      const destroySelect2 = () => {
        if (select2Instance) {
          try {
            // Unbind events
            $(selectElement.value).off('select2:select');
            $(selectElement.value).off('select2:unselect');
            $(selectElement.value).off('change');
            
            // Destroy Select2 instance
            select2Instance.select2('destroy');
            select2Instance = null;
          } catch (error) {
            console.error('Error destroying Select2:', error);
          }
        }
      };
  
      // Watch for modelValue changes
      watch(() => props.modelValue, (newValue) => {
        if (selectElement.value) {
          $(selectElement.value).val(newValue).trigger('change');
        }
      }, { deep: true });
  
      // Watch for disabled changes
      watch(() => props.disabled, (newValue) => {
        if (selectElement.value) {
          if (newValue) {
            $(selectElement.value).prop('disabled', true);
          } else {
            $(selectElement.value).prop('disabled', false);
          }
          $(selectElement.value).trigger('change');
        }
      });
  
      onMounted(() => {
        // Wait for DOM to be ready
        setTimeout(() => {
          initializeSelect2();
        }, 0);
      });
  
      onBeforeUnmount(() => {
        destroySelect2();
      });
  
      return {
        selectElement
      };
    }
  };
  </script>