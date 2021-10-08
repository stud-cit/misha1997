<template>
  <div id="export" v-show="false">
      <template v-if="articleReport.length > 0">
          <h2>Стаття-доповідь у матеріалах наукових конференціях</h2>
          <ol>
              <li v-for="(item, index) in articleReport" :key="index" style="text-align: justify">
                  {{item.initials}} {{item.title}}. <i>{{item.name_magazine}}</i>. {{item.year}}. <span v-if="item.number">{{ item.number }}.</span><span v-if="item.pages"> C. {{item.pages}}.</span> <span v-if="item.doi">DOI: {{item.doi}}.</span>
              </li>
          </ol>
      </template>

      <template v-if="articleProfessional.length > 0">
          <h2>Стаття у фахових виданнях України Категорії Б</h2>
          <ol>
              <li v-for="(item, index) in articleProfessional" :key="index" style="text-align: justify">
                  {{item.initials}} {{ item.title }}. <i>{{ item.name_magazine }}</i>. {{ item.year }}. <span v-if="item.number">{{ item.number}}.</span><span v-if="item.pages"> C. {{item.pages}}.</span> <span v-if="item.doi">DOI: {{item.doi}}.</span>
              </li>
          </ol>
      </template>

      <template v-if="otherArticles.length > 0">
          <h2>Інші статті</h2>
          <ol>
              <li v-for="(item, index) in otherArticles" :key="index" style="text-align: justify">
                  {{item.initials}} {{ item.title }}. <i>{{ item.name_magazine }}</i>. {{ item.year }}. <span v-if="item.number">{{ item.number }}.</span> C. {{ item.pages }}. <span v-if="item.doi">DOI: {{ item.doi }}.</span>
              </li>
          </ol>
      </template>

      <template v-if="monograph.length > 0">
          <h2>Монографії</h2>
          <ol>
              <li v-for="(item, index) in monograph" :key="index" style="text-align: justify">
              {{ item.initials }} {{ item.title }}: {{ item.publication_type.title.toLowerCase() }}<span v-if="item.by_editing"> / за ред. {{ item.by_editing }}</span>. {{ item.city }}: ПФ «Видавництво «{{ item.editor_name }}», {{ item.year }}. {{ item.pages }} c. <span v-if="item.doi">DOI: {{ item.doi }}.</span>
              </li>
          </ol>
      </template>

      <template v-if="parts.length > 0">
          <h2>Розділ монографії / розділ книги</h2>
          <ol>
              <li v-for="(item, index) in parts" :key="index" style="text-align: justify">
              {{ item.initials }} {{ item.title }}. <i>{{ item.name_monograph }}</i>: <span v-if="item.by_editing"> / за ред. {{ item.by_editing }}.</span> {{ item.city }}: {{ item.editor_name }}, {{ item.year }}. C. {{ item.pages }}.
              </li>
          </ol>
      </template>

      <template v-if="books.length > 0">
          <h2>Навчальні посібники, підручники</h2>
          <ol>
              <li v-for="(item, index) in books" :key="index" style="text-align: justify">
              {{ item.initials }} {{ item.title }}: {{ item.publication_type.title.toLowerCase() }}<span v-if="item.by_editing"> / за ред. {{ item.by_editing }}</span>. {{ item.city }}: {{ item.editor_name }}, {{ item.year }}. {{ item.pages }} c.
              </li>
          </ol>
      </template>

      <template v-if="patents.length > 0">
          <h2>Патент</h2>
          <ol>
              <li v-for="(item, index) in patents" :key="index" style="text-align: justify">
              {{ item.title }} Пат. {{ item.number_certificate }} {{ item.country }}: МПК{{ item.mpk }}. №{{ item.application_number }}; заявл. {{ item.date_application }}; опубл. {{ item.date_publication }}, Бюл. № {{ item.newsletter_number }}.
              </li>
          </ol>
      </template>

      <template v-if="certificates.length > 0">
          <h2>Свідоцтво про реєстрацію авторських прав на твір / рішення</h2>
          <ol>
              <li v-for="(item, index) in certificates" :key="index" style="text-align: justify">
              Свідоцтво про реєстрацію авторського права на твір «{{item.title}}» № {{item.number_certificate}} {{item.country}} / {{item.initials}}; <span v-if="item.applicant">{{item.applicant}};</span> заяв. {{item.date_application}}; опубл. {{item.date_publication}}.
              </li>
          </ol>
      </template>

      <template v-if="methodicals.length > 0">
          <h2>Методичні вказівки</h2>
          <ol>
              <li v-for="(item, index) in methodicals" :key="index" style="text-align: justify">
              {{item.initials}} {{item.title}}. {{item.city}}: <span v-if="item.editor_name">{{item.editor_name}}, </span>{{item.year}}. {{item.pages}} c.
              </li>
          </ol>
      </template>

      <template v-if="electronics.length > 0">
          <h2>Електронні видання</h2>
          <ol>
              <li v-for="(item, index) in electronics" :key="index" style="text-align: justify">
              {{item.initials}}. {{item.title}}: {{item.publication_type.title.toLowerCase()}}; [електронний ресурс] / {{item.initials}}. {{item.city}}: <span v-if="item.editor_name">{{item.editor_name}}, </span>{{item.year}}. {{item.pages}} c. URL: {{item.url}}.
              </li>
          </ol>
      </template>

      <template v-if="thesis.length > 0">
          <h2>Тези доповіді</h2>
          <ol>
              <li v-for="(item, index) in thesis" :key="index" style="text-align: justify">
              {{item.initials}} {{item.title}}. <i>{{item.name_conference}}</i>: тези доповідей<span v-if="item.by_editing"> / за ред. {{item.by_editing}}</span>. {{item.city}}: <span v-if="item.editor_name">{{item.editor_name}},</span> {{item.year}}. C. {{item.pages}}. <span v-if="item.doi">DOI: {{item.doi}}.</span>
              </li>
          </ol>
      </template>
  </div>
</template>
<script>
export default {
  props: {
    articleProfessional: [],
    otherArticles: [],
    articleReport: [],
    monograph: [],
    books: [],
    patents: [],
    certificates: [],
    methodicals: [],
    electronics: [],
    thesis: [],
    parts: []
  }
}
</script>