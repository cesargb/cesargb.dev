<template>
  <div class="experience-item">
    <nuxt-link v-if="experience.link" :to="experience.link" class="link">
      <i class="icon" :class="experience.icon"></i>
      <span v-if="experience.title">{{ experience.title }}</span>
    </nuxt-link>
    <div  v-else class="link">
      <i class="icon" :class="experience.icon"></i>
      <span v-if="experience.title">{{ experience.title }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    experience: {
      type: Object,
      required: true
    }
  },
}
</script>

<style lang="scss">
  .experience-item {
    transition: all 0.3s ease;

    .link {
      display: flex;
      flex-direction: column;
      gap: 2px;
      align-items: center;
      text-decoration: none;

      span {
        font-size: 0.8rem;
      }

      &:focus-visible {
        outline: none !important;
      }
    }

    &:hover, &:focus-within{
      z-index: 1;
      transform: scale(1.5);
      color: #ccc;
    }

  }
</style>
