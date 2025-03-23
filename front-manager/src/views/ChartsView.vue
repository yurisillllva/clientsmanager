<template>
  <div class="container">
    <h2>Dados sobre os contatos</h2>
    <br />
    <div class="card">
      <div v-if="statesData && statesData.length" class="mx-4 py-2">
        <h3>Segmentação por Estado</h3>
        <pie-chart :data="statesData" :labels="statesLabels" />
      </div>
      <br />
      <br />

      <div v-if="citiesData && citiesData.length" class="mx-4 py-2">
        <h3>Segmentação por Cidade</h3>
        <pie-chart :data="citiesData" :labels="citiesLabels" />
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import PieChart from "@/components/PieChart.vue";

export default {
  data() {
    return {
      statesData: [],
      statesLabels: [],
      citiesData: [],
      citiesLabels: [],
    };
  },
  components: {
    PieChart,
  },
  mounted() {
    this.fetchChartData();
  },
  methods: {
    async fetchChartData() {
      try {
        // rotas da API
        const statesResponse = await axios.get("/charts/states");
        const citiesResponse = await axios.get("/charts/cities");

        this.statesData = statesResponse.data.data;
        this.statesLabels = statesResponse.data.labels;

        this.citiesData = citiesResponse.data.data;
        this.citiesLabels = citiesResponse.data.labels;
      } catch (error) {
        console.error("Erro ao buscar dados dos gráficos:", error);
      }
    },
  },
};
</script>

<style scoped>
</style>
