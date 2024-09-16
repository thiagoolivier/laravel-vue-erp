<template>
    <div class="chart-card">
        <div class="chart-title min-h-[20%]">
            <span class="self-center">{{ props.title }}</span>
        </div>
        <div class="min-h-[80%] p-4">
            <Bar id="barChart" :data="props.chartData" :options="options" />
        </div>
    </div>
</template>

<script setup lang="ts">
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    type ChartData
} from 'chart.js';
import { Bar } from 'vue-chartjs';

const props = defineProps<{
    title: string,
    chartData: ChartData<'bar'>
}>();

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
    }
}
</script>

<style scoped>
canvas {
    background-color: inherit;
}
</style>