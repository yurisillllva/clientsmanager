<template>
  <div class="charts-container">
    <canvas id="statesChart"></canvas>
    <canvas id="citiesChart"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';

export default {
  async mounted() {
    const { data: states } = await axios.get('charts/states');
    const { data: cities } = await axios.get('charts/cities');
    
    new Chart(document.getElementById('statesChart'), {
      type: 'pie',
      data: {
        labels: states.labels,
        datasets: [{
          data: states.data,
          backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe']
        }]
      }
    });

    // Repetir para cities
  }
}
</script>