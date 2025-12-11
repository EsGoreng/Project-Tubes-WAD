import ApexCharts from "apexcharts";

// Get the CSS variable --color-brand and convert it to hex for ApexCharts
const getBrandColor = () => {
    // Get the computed style of the document's root element
    const computedStyle = getComputedStyle(document.documentElement);

    // Get the value of the --color-brand CSS variable
    return (
        computedStyle.getPropertyValue("--color-fg-brand").trim() || "#1447E6"
    );
};

const getBrandSecondaryColor = () => {
    const computedStyle = getComputedStyle(document.documentElement);
    return (
        computedStyle.getPropertyValue("--color-fg-brand-subtle").trim() ||
        "#1447E6"
    );
};

const getBrandTertiaryColor = () => {
    const computedStyle = getComputedStyle(document.documentElement);
    return (
        computedStyle.getPropertyValue("--color-fg-brand-strong").trim() ||
        "#1447E6"
    );
};

const getNeutralPrimaryColor = () => {
    const computedStyle = getComputedStyle(document.documentElement);
    return (
        computedStyle.getPropertyValue("--color-neutral-primary").trim() ||
        "#1447E6"
    );
};

const brandColor = getBrandColor();
const brandSecondaryColor = getBrandSecondaryColor();
const brandTertiaryColor = getBrandTertiaryColor();
const neutralPrimaryColor = getNeutralPrimaryColor();

const getChartOptions = () => {
    return {
        series: [52.8, 26.8, 20.4],
        colors: [brandColor, brandSecondaryColor, brandTertiaryColor],
        chart: {
            height: 420,
            width: "100%",
            type: "pie",
        },
        stroke: {
            colors: [neutralPrimaryColor],
            lineCap: "",
        },
        plotOptions: {
            pie: {
                labels: {
                    show: true,
                },
                size: "100%",
                dataLabels: {
                    offset: -25,
                },
            },
        },
        labels: ["Direct", "Organic search", "Referrals"],
        dataLabels: {
            enabled: true,
            style: {
                fontFamily: "Inter, sans-serif",
            },
        },
        legend: {
            position: "bottom",
            fontFamily: "Inter, sans-serif",
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return value + "%";
                },
            },
        },
        xaxis: {
            labels: {
                formatter: function (value) {
                    return value + "%";
                },
            },
            axisTicks: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
        },
    };
};

if (document.getElementById("pie-chart") && typeof ApexCharts !== "undefined") {
    const chart = new ApexCharts(
        document.getElementById("pie-chart"),
        getChartOptions()
    );
    chart.render();
}
