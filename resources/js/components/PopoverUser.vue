<template>
  <ul class="popover-list">
    <li v-show="authorItem.author.job"><b>Місце роботи:</b> {{ authorItem.author.job }};</li>
    <li v-show="authorItem.author.position"><b>Посада:</b> {{ authorItem.author.position }};</li>
    <li v-show="authorItem.author.academic_code"><b>Академічна група:</b> {{ authorItem.author.academic_code }};</li>
    <li v-show="authorItem.author.faculty"><b>Інститут/факультет:</b> {{ authorItem.author.faculty }};</li>
    <li v-show="authorItem.author.department"><b>Кафедра/підрозділ:</b> {{ authorItem.author.department }};</li>
  </ul>
</template>
<script>
export default {
  props: {
    authorItem: Object
  }
}
</script>