<template>
  <div class="page-index">

    <terminal title="cesargb.dev">
      <h1 class="primary">
        César García ><span class="cursor">_</span>
      </h1>
      <h2 class="yellow">
        Desarrollador de software y arquitecto de infraestructuras.
      </h2>
      <div class="experience">
        <div>
          Experiencia con más de 25 años:
        </div>
        <div>
          <div>
            Developer:
          </div>
          <div>
            <experience-item v-for="item in experience.developer" :key="item.link" :experience="item" />
          </div>

        </div>

        <div>
          <div>
            DevOps:
          </div>
          <div>
            <experience-item v-for="item in experience.devops" :key="item.link" :experience="item" />
          </div>
        </div>

        <div>
          <div>
            IT Architect:
          </div>
          <div>
            <experience-item v-for="item in experience.it" :key="item.link" :experience="item" />
          </div>
        </div>
      </div>
    </terminal>

  </div>
</template>

<script>
import ExperienceItem from '~/components/ExperienceItem.vue'
export default {
  components: { ExperienceItem },
  data() {
    return {
      experience: {
        developer: [
            {
              title: 'laravel',
              link: 'experiencia/laravel',
              icon: 'laravel',
            },
            {
              title: 'vue',
              link: 'experiencia/vue',
              icon: 'vue',
            },
        ],
        devops: [
            {
              title: 'ci/cd',
              link: null,
              icon: 'git',
            },
        ],
        it: [
            {
              title: 'linux',
              link: 'experiencia/linux',
              icon: 'linux',
            },
            {
              title: 'aws',
              link: null,
              icon: 'aws',
            },
        ]
      }
    }
  }
}
</script>

<style lang="sass">
  @use '~/assets/css/pages/index.scss'
</style>
