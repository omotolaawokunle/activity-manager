<template>
  <div
    v-if="alertOpen"
    class="relative flex items-center px-6 py-4 mb-4 border-0 rounded"
    :class="
      type == 'success'
        ? 'bg-green-200 text-green-600'
        : 'bg-red-200 text-red-600'
    "
  >
    <span class="inline-block mr-5 text-xl align-middle">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-5 h-5"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
        />
      </svg>
    </span>
    <span class="inline-block mr-8 font-bold align-middle">
      <slot></slot>
    </span>
    <button
      class="absolute top-0 right-0 mt-4 mr-6 text-2xl font-semibold leading-none bg-transparent outline-none focus:outline-none"
      v-on:click="closeAlert()"
    >
      <span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-5 h-5"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path
            fill-rule="evenodd"
            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
            clip-rule="evenodd"
          />
        </svg>
      </span>
    </button>
  </div>
</template>

<script>
export default {
  name: "alert",
  props: {
    type: {
      default: "success",
      type: String,
    },
  },
  data() {
    return {
      alertOpen: true,
    };
  },
  methods: {
    closeAlert: function () {
      this.alertOpen = false;
    },
  },
};
</script>
