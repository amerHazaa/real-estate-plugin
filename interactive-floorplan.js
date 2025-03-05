export default function initializeInteractiveFloorplan(containerSelector) {
  const containers = document.querySelectorAll(containerSelector);

  containers.forEach((el) => {
    const rawData = el.getAttribute("data-layout") || "";
    let layout = {};
    if (rawData.trim() !== "") {
      try {
        layout = JSON.parse(rawData);
      } catch (e) {
        layout = {};
      }
    } else {
      // استخدام كائن افتراضي لتفادي null
      layout = { rooms: [], doors: [], windows: [] };
    }

    const width = el.offsetWidth > 0 ? el.offsetWidth : 600;
    const height = el.offsetHeight > 0 ? el.offsetHeight : 400;

    const canvas = document.createElement("canvas");
    canvas.width = width;
    canvas.height = height;
    el.appendChild(canvas);

    const ctx = canvas.getContext("2d");

    drawLayout(ctx, layout);
  });

  function drawLayout(ctx, layout) {
    // رسم الغرف
    if (layout.rooms && Array.isArray(layout.rooms)) {
      layout.rooms.forEach((room) => {
        const x = typeof room.x === 'number' ? room.x : 0;
        const y = typeof room.y === 'number' ? room.y : 0;
        const w = typeof room.width === 'number' ? room.width : 50;
        const h = typeof room.height === 'number' ? room.height : 50;
        const name = room.name || "Room";

        ctx.fillStyle = "#4caf50";
        ctx.fillRect(x, y, w, h);
        ctx.fillStyle = "#fff";
        ctx.fillText(name, x + 5, y + 15);
      });
    }

    // رسم الأبواب
    if (layout.doors && Array.isArray(layout.doors)) {
      ctx.fillStyle = "#795548";
      layout.doors.forEach((door) => {
        const x = typeof door.x === 'number' ? door.x : 0;
        const y = typeof door.y === 'number' ? door.y : 0;
        // لا نقوم بتمرير قيم فارغة لنصف القطر
        ctx.beginPath();
        ctx.arc(x, y, 5, 0, 2 * Math.PI);
        ctx.fill();
      });
    }

    // رسم النوافذ
    if (layout.windows && Array.isArray(layout.windows)) {
      ctx.fillStyle = "#03a9f4";
      layout.windows.forEach((window) => {
        const x = typeof window.x === 'number' ? window.x : 0;
        const y = typeof window.y === 'number' ? window.y : 0;
        ctx.fillRect(x, y, 10, 10);
      });
    }
  }
}
export default function initializeInteractiveFloorplan(containerSelector) {
  const containers = document.querySelectorAll(containerSelector);

  containers.forEach((el) => {
    let layout = { rooms: [], doors: [], windows: [] };
    
    try {
      const rawData = el.getAttribute("data-layout");
      if (rawData && rawData.trim() !== "") {
        layout = JSON.parse(rawData);
      }
    } catch (e) {
      console.error("خطأ في تحليل بيانات المخطط: ", e);
    }

    const width = el.offsetWidth > 0 ? el.offsetWidth : 600;
    const height = el.offsetHeight > 0 ? el.offsetHeight : 400;

    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("width", width);
    svg.setAttribute("height", height);
    svg.style.border = "1px solid #ccc";
    el.appendChild(svg);

    drawLayout(svg, layout);
    makeInteractive(svg);
  });
}

function drawLayout(svg, layout) {
  layout.rooms.forEach((room) => {
    const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
    rect.setAttribute("x", room.x || 0);
    rect.setAttribute("y", room.y || 0);
    rect.setAttribute("width", room.width || 50);
    rect.setAttribute("height", room.height || 50);
    rect.setAttribute("fill", "#4caf50");
    rect.setAttribute("stroke", "#000");
    rect.setAttribute("stroke-width", "2");
    svg.appendChild(rect);

    const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
    text.setAttribute("x", (room.x || 0) + 5);
    text.setAttribute("y", (room.y || 0) + 15);
    text.setAttribute("fill", "#fff");
    text.textContent = room.name || "Room";
    svg.appendChild(text);
  });

  layout.doors.forEach((door) => {
    const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    circle.setAttribute("cx", door.x || 0);
    circle.setAttribute("cy", door.y || 0);
    circle.setAttribute("r", 5);
    circle.setAttribute("fill", "#795548");
    svg.appendChild(circle);
  });

  layout.windows.forEach((window) => {
    const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
    rect.setAttribute("x", window.x || 0);
    rect.setAttribute("y", window.y || 0);
    rect.setAttribute("width", 10);
    rect.setAttribute("height", 10);
    rect.setAttribute("fill", "#03a9f4");
    svg.appendChild(rect);
  });
}

function makeInteractive(svg) {
  let selectedElement = null;
  let offsetX = 0, offsetY = 0;
  
  svg.addEventListener("mousedown", (event) => {
    if (event.target.tagName === "rect" || event.target.tagName === "circle") {
      selectedElement = event.target;
      offsetX = event.clientX - parseFloat(selectedElement.getAttribute("x"));
      offsetY = event.clientY - parseFloat(selectedElement.getAttribute("y"));
    }
  });

  svg.addEventListener("mousemove", (event) => {
    if (selectedElement) {
      selectedElement.setAttribute("x", event.clientX - offsetX);
      selectedElement.setAttribute("y", event.clientY - offsetY);
    }
  });

  svg.addEventListener("mouseup", () => {
    selectedElement = null;
  });
}
