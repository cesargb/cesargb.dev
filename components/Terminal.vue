<template>
  <div class="terminal" :class="{hidden: hided}" @dblclick="show()">
    <div class="terminal__header">
      <div>
        <button @click="hide()">x</button>
      </div>
      <div class="terminal__header__title">{{ title }}</div>
    </div>
    <div class="terminal__body">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: 'Terminal'
    },
  },

  data() {
    return {
      hided: false
    }
  },

  methods: {
    hide() {
      this.hided = true
    },

    show() {
      this.hided = false
    }
  }
}
</script>

<style lang="scss">
  @use '~/assets/css/terminal.scss'
</style>
