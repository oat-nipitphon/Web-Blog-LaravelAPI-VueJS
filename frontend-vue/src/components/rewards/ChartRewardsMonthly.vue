<script setup>
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";
import { Bar } from "vue-chartjs";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);

const chartData = {
  labels: ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย."],
  datasets: [
    {
      label: "แลกของรางวัล",
      backgroundColor: "#3b82f6",
      data: [40, 55, 75, 60, 80, 95],
    },
  ],
};

const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      position: "top",
    },
    title: {
      display: true,
      text: "สถิติการแลกรางวัล (รายเดือน)",
    },
  },
};
</script>

<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>
