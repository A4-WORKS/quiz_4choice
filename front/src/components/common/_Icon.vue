<template>
  <i v-if="callTypeIco" :class="iconSettingsClass" :style="iconSettingsStyle"/>
  <i v-else :class="iconCss" :style="style"/>
  <!--<div>-->
  <!--{{style}}-->
  <!--<i :class="iconCss" :style="style"/>-->
  <!--</div>-->
</template>

<script>
export default {
  props: ['set', 'i', 'c', 'pr', 'pl', 'fs'],
  computed: {
    callTypeIco: function () {

      if (typeof this.set === 'undefined') {
        return false
      } else {
        return true
      }

    },
    iconSettingsClass: function () {
      return this.set.class
    },
    iconSettingsStyle: function () {
      return this.set.style
    },
    iconCss: function () {

      switch (this.i) {
        case 'bars':
          return 'fas fa-bars'
        case 'caret-square-right':
          return 'fas fa-caret-square-right'
        case 'checkbox-none':
          return 'fas fa-square'
        case 'checkbox-checked':
          return 'fas fa-check-square'
        case 'share-square':
          return 'fas fa-share-square'
        case 'edit':
          return 'fas fa-edit'
        case 'tasks':
          return 'fas fa-tasks'
        case 'plus-square':
          return 'fas fa-plus-square'
        case 'minus-circle':
          return 'fas fa-minus-circle'
        case 'toggle-on':
          return 'fas fa-toggle-on'
        case 'user-check':
          return 'fas fa-user-check'
        case 'check':
          return 'far fa-check-square'
        case 'bookmark':
          return 'far fa-bookmark'

        default:
          // unknown-icon
          return 'far fa-question-circle'
      }
    },
    style: function () {

      let style = []

      if (this.pr) {
        style.push('padding-right:' + this.pr + 'px')
      } else {
        style.push('padding-right:5px')
      }

      if (this.pl) {
        style.push('padding-left:' + this.pr + 'px')
      }
      if (this.c) {
        style.push('color:' + this.c)
      }

      if (this.fs) {
        style.push('font-size:' + this.fs + 'px')
      }

      return style.join(';')
    }
  }
}
</script>
