<template>
    <div class="grid grid-cols-1 md:grid-cols-2 md:grid-rows-4 gap-4 p-4 min-h-full">
        <BarChart class="row-span-2" />
        <LineChart class="row-span-2" />
    </div>
</template>

<script setup lang="ts">
import BarChart from '@/components/charts/BarChart.vue';
import LineChart from '@/components/charts/LineChart.vue';

</script>