<template>
  <div class="chart-container">
    <canvas ref="pieChart"></canvas>
  </div>
</template>

<script>
import { Chart, PieController, ArcElement, Tooltip, Legend } from 'chart.js';

// Registre os controladores e elementos necessários
Chart.register(PieController, ArcElement, Tooltip, Legend);

export default {
  props: {
    data: Array,
    labels: Array,
  },
  mounted() {
    this.renderChart();
  },
  methods: {
    renderChart() {
      const ctx = this.$refs.pieChart.getContext('2d');

      // Calcula o total dos dados
      const total = this.data.reduce((acc, value) => acc + value, 0);

      // Cria os dados do gráfico com percentuais
      const dataWithPercentages = {
        labels: this.labels,
        datasets: [{
          data: this.data,
          backgroundColor: ['#060321', '#180d6e', '#2715b0', '#321bde', '#5946e4', '#9f94af', '#9f94ef'],
        }]
      };

      // Cria o gráfico
      new Chart(ctx, {
        type: 'pie',
        data: dataWithPercentages,
        options: {
          responsive: true,
          layout: {
            padding: {
              right: 200, 
            }
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: (context) => {
                  const label = context.label || '';
                  const value = context.raw || 0;
                  const percentage = ((value / total) * 100).toFixed(2) + '%';
                  return `${label}: ${value} (${percentage})`;
                }
              }
            },
            legend: {
              position: 'right',
              labels: {
                generateLabels: (chart) => {
                  const data = chart.data;
                  return data.labels.map((label, index) => {
                    const value = data.datasets[0].data[index];
                    const percentage = ((value / total) * 100).toFixed(2) + '%';
                    return {
                      text: `${label}: ${value} (${percentage})`,
                      fillStyle: data.datasets[0].backgroundColor[index],
                      hidden: false,
                      index: index,
                    };
                  });
                }
              }
            }
          }
        }
      });
    }
  }
};
</script>

<style scoped>
.chart-container {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  width: 100%;
  max-width: 1200px; 
  margin: 0 auto;
}

canvas {
  max-width: 100%;
  max-height: 400px;
}
</style>