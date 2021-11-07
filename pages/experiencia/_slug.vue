<template>
  <div class="article" tabindex="0" @keydown.esc="$router.go(-1)">
    <div class="close">
      <button  @click="$router.go(-1)">+</button>
    </div>

    <article>
      <h1>{{ page.title }}</h1>
      <nuxt-content :document="page" />
    </article>
  </div>
</template>

<script>
export default {
  async asyncData ({ params, $content }) {
    const page = await $content(`experiencia/${params.slug}`).fetch()

    return {
      page
    }
  }
}
</script>

<style lang="sass">
@use '~/assets/css/article.scss'
</style>
