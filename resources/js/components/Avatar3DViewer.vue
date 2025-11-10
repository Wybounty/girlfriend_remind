<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';
import * as THREE from 'three';

interface Props {
    characteristics: {
        skin_tone?: string;
        hair_color?: string;
        hair_length?: string;
        eye_color?: string;
        gender?: string;
        body_type?: string;
        clothing_color_1?: string;
        clothing_color_2?: string;
    };
    width?: number;
    height?: number;
}

const props = withDefaults(defineProps<Props>(), {
    width: 800,
    height: 600,
});

const canvasRef = ref<HTMLCanvasElement | null>(null);
let scene: THREE.Scene;
let camera: THREE.PerspectiveCamera;
let renderer: THREE.WebGLRenderer;
let avatar: THREE.Group;
let animationId: number;

// Variables pour le mouvement aléatoire
let targetPosition = new THREE.Vector3();
let currentPosition = new THREE.Vector3(0, 0, 0);
let isMoving = false;
let moveSpeed = 0.02;
let waitTime = 0;
let maxWaitTime = 100; // frames à attendre avant le prochain mouvement

// Limites de la chambre
const roomBounds = {
    minX: -4,
    maxX: 4,
    minZ: -4,
    maxZ: 4,
};

// Mapper les tons de peau vers des couleurs THREE
const skinToneMap: Record<string, string> = {
    'very pale': '#FFE4C4',
    'pale': '#FDD7B3',
    'light': '#F1C6A5',
    'medium': '#E3B08E',
    'tan': '#D9A066',
    'brown': '#8D5524',
    'dark brown': '#6B4226',
    'very dark': '#4A2511',
};

const hairColorMap: Record<string, string> = {
    'blonde': '#F4E4C1',
    'light brown': '#C4A588',
    'brown': '#8B6F47',
    'dark brown': '#5C4033',
    'black': '#1a1a1a',
    'red': '#A5301D',
    'gray': '#9CA3AF',
    'white': '#E5E7EB',
};

onMounted(() => {
    initScene();
    createAvatar();
    animate();
});

onUnmounted(() => {
    if (animationId) {
        cancelAnimationFrame(animationId);
    }
    if (renderer) {
        renderer.dispose();
    }
});

watch(() => props.characteristics, () => {
    if (avatar) {
        updateAvatar();
    }
}, { deep: true });

function initScene() {
    if (!canvasRef.value) return;

    // Créer la scène
    scene = new THREE.Scene();
    scene.background = new THREE.Color(0x87CEEB); // Ciel bleu clair

    // Créer la caméra (vue du côté face à vous)
    camera = new THREE.PerspectiveCamera(
        60,
        props.width / props.height,
        0.1,
        1000
    );
    camera.position.set(0, 8, 12); // Vue de face depuis le côté
    camera.lookAt(0, 0, 0);

    // Créer le renderer
    renderer = new THREE.WebGLRenderer({
        canvas: canvasRef.value,
        antialias: true,
    });
    renderer.setSize(props.width, props.height);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;

    // Ajouter des lumières
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(5, 15, 5);
    directionalLight.castShadow = true;
    directionalLight.shadow.mapSize.width = 2048;
    directionalLight.shadow.mapSize.height = 2048;
    directionalLight.shadow.camera.near = 0.5;
    directionalLight.shadow.camera.far = 50;
    directionalLight.shadow.camera.left = -10;
    directionalLight.shadow.camera.right = 10;
    directionalLight.shadow.camera.top = 10;
    directionalLight.shadow.camera.bottom = -10;
    scene.add(directionalLight);

    // Créer la chambre
    createRoom();
    
    // Ajouter des meubles
    createFurniture();
}

function createAvatar() {
    avatar = new THREE.Group();

    const skinColor = new THREE.Color(
        skinToneMap[props.characteristics.skin_tone || 'medium'] || '#E3B08E'
    );
    const hairColor = new THREE.Color(
        hairColorMap[props.characteristics.hair_color || 'brown'] || '#8B6F47'
    );
    const clothingColor1 = new THREE.Color(props.characteristics.clothing_color_1 || '#808080');
    const clothingColor2 = new THREE.Color(props.characteristics.clothing_color_2 || '#ffffff');

    // Tête
    const headGeometry = new THREE.SphereGeometry(0.5, 32, 32);
    const headMaterial = new THREE.MeshStandardMaterial({ color: skinColor });
    const head = new THREE.Mesh(headGeometry, headMaterial);
    head.position.y = 1.5;
    avatar.add(head);

    // Corps
    const bodyGeometry = new THREE.CylinderGeometry(0.4, 0.5, 1.2, 32);
    const bodyMaterial = new THREE.MeshStandardMaterial({ color: clothingColor1 });
    const body = new THREE.Mesh(bodyGeometry, bodyMaterial);
    body.position.y = 0.4;
    avatar.add(body);

    // Cheveux (simple)
    const hairGeometry = new THREE.SphereGeometry(0.52, 32, 32, 0, Math.PI * 2, 0, Math.PI / 2);
    const hairMaterial = new THREE.MeshStandardMaterial({ color: hairColor });
    const hair = new THREE.Mesh(hairGeometry, hairMaterial);
    hair.position.y = 1.7;
    avatar.add(hair);

    // Yeux
    const eyeGeometry = new THREE.SphereGeometry(0.08, 16, 16);
    const eyeColor = new THREE.Color(
        props.characteristics.eye_color === 'blue' ? '#5B9BD5' :
        props.characteristics.eye_color === 'green' ? '#70AD47' :
        props.characteristics.eye_color === 'brown' ? '#7F6545' : '#7F6545'
    );
    const eyeMaterial = new THREE.MeshStandardMaterial({ color: eyeColor });
    
    const leftEye = new THREE.Mesh(eyeGeometry, eyeMaterial);
    leftEye.position.set(-0.15, 1.55, 0.4);
    avatar.add(leftEye);

    const rightEye = new THREE.Mesh(eyeGeometry, eyeMaterial);
    rightEye.position.set(0.15, 1.55, 0.4);
    avatar.add(rightEye);

    // Bras
    const armGeometry = new THREE.CylinderGeometry(0.12, 0.12, 1, 16);
    const armMaterial = new THREE.MeshStandardMaterial({ color: skinColor });

    const leftArm = new THREE.Mesh(armGeometry, armMaterial);
    leftArm.position.set(-0.6, 0.4, 0);
    leftArm.rotation.z = Math.PI / 6;
    avatar.add(leftArm);

    const rightArm = new THREE.Mesh(armGeometry, armMaterial);
    rightArm.position.set(0.6, 0.4, 0);
    rightArm.rotation.z = -Math.PI / 6;
    avatar.add(rightArm);

    // Jambes
    const legGeometry = new THREE.CylinderGeometry(0.15, 0.15, 1, 16);
    const legMaterial = new THREE.MeshStandardMaterial({ color: clothingColor2 });

    const leftLeg = new THREE.Mesh(legGeometry, legMaterial);
    leftLeg.position.set(-0.2, -0.7, 0);
    avatar.add(leftLeg);

    const rightLeg = new THREE.Mesh(legGeometry, legMaterial);
    rightLeg.position.set(0.2, -0.7, 0);
    avatar.add(rightLeg);

    scene.add(avatar);
}

function updateAvatar() {
    // Mettre à jour les couleurs de l'avatar existant
    if (!avatar) return;

    const skinColor = new THREE.Color(
        skinToneMap[props.characteristics.skin_tone || 'medium'] || '#E3B08E'
    );
    const hairColor = new THREE.Color(
        hairColorMap[props.characteristics.hair_color || 'brown'] || '#8B6F47'
    );

    // Mettre à jour les matériaux
    avatar.children.forEach((child) => {
        if (child instanceof THREE.Mesh) {
            // Logique de mise à jour basée sur la position
            if (child.position.y > 1.4) {
                // Tête ou cheveux
                if (child.position.y > 1.6) {
                    (child.material as THREE.MeshStandardMaterial).color = hairColor;
                } else {
                    (child.material as THREE.MeshStandardMaterial).color = skinColor;
                }
            }
        }
    });
}

function createRoom() {
    // Sol
    const floorGeometry = new THREE.PlaneGeometry(10, 10);
    const floorMaterial = new THREE.MeshStandardMaterial({ 
        color: 0xD2B48C, // Beige pour le parquet
        roughness: 0.8,
    });
    const floor = new THREE.Mesh(floorGeometry, floorMaterial);
    floor.rotation.x = -Math.PI / 2;
    floor.receiveShadow = true;
    scene.add(floor);

    // Murs
    const wallMaterial = new THREE.MeshStandardMaterial({ 
        color: 0xF5E6D3, // Beige clair
        side: THREE.DoubleSide,
    });

    // Mur du fond
    const backWallGeometry = new THREE.PlaneGeometry(10, 6);
    const backWall = new THREE.Mesh(backWallGeometry, wallMaterial);
    backWall.position.set(0, 3, -5);
    scene.add(backWall);

    // Mur gauche
    const leftWallGeometry = new THREE.PlaneGeometry(10, 6);
    const leftWall = new THREE.Mesh(leftWallGeometry, wallMaterial);
    leftWall.position.set(-5, 3, 0);
    leftWall.rotation.y = Math.PI / 2;
    scene.add(leftWall);

    // Mur droit
    const rightWallGeometry = new THREE.PlaneGeometry(10, 6);
    const rightWall = new THREE.Mesh(rightWallGeometry, wallMaterial);
    rightWall.position.set(5, 3, 0);
    rightWall.rotation.y = -Math.PI / 2;
    scene.add(rightWall);

    // Pas de plafond pour une vue de dessus ouverte
}

function createFurniture() {
    // Lit
    const bedGeometry = new THREE.BoxGeometry(2, 0.5, 3);
    const bedMaterial = new THREE.MeshStandardMaterial({ color: 0xFF69B4 }); // Rose
    const bed = new THREE.Mesh(bedGeometry, bedMaterial);
    bed.position.set(-3, 0.25, -3);
    bed.castShadow = true;
    bed.receiveShadow = true;
    scene.add(bed);

    // Bureau
    const deskGeometry = new THREE.BoxGeometry(2, 0.1, 1);
    const deskMaterial = new THREE.MeshStandardMaterial({ color: 0x8B4513 }); // Marron
    const desk = new THREE.Mesh(deskGeometry, deskMaterial);
    desk.position.set(3, 1, -3);
    desk.castShadow = true;
    desk.receiveShadow = true;
    scene.add(desk);

    // Pieds du bureau
    const legGeometry = new THREE.BoxGeometry(0.1, 1, 0.1);
    const legMaterial = new THREE.MeshStandardMaterial({ color: 0x654321 });
    
    for (let i = 0; i < 4; i++) {
        const leg = new THREE.Mesh(legGeometry, legMaterial);
        const xOffset = i % 2 === 0 ? -0.9 : 0.9;
        const zOffset = i < 2 ? -0.4 : 0.4;
        leg.position.set(3 + xOffset, 0.5, -3 + zOffset);
        leg.castShadow = true;
        scene.add(leg);
    }

    // Tapis
    const rugGeometry = new THREE.PlaneGeometry(3, 3);
    const rugMaterial = new THREE.MeshStandardMaterial({ 
        color: 0xDC143C, // Rouge
        roughness: 0.9,
    });
    const rug = new THREE.Mesh(rugGeometry, rugMaterial);
    rug.rotation.x = -Math.PI / 2;
    rug.position.set(0, 0.01, 0);
    scene.add(rug);
}

function getRandomPosition() {
    const x = Math.random() * (roomBounds.maxX - roomBounds.minX) + roomBounds.minX;
    const z = Math.random() * (roomBounds.maxZ - roomBounds.minZ) + roomBounds.minZ;
    
    // Éviter les meubles
    if ((x < -2 && x > -4 && z < -2 && z > -4) || // Lit
        (x > 2 && x < 4 && z < -2 && z > -4)) {    // Bureau
        return getRandomPosition(); // Réessayer
    }
    
    return new THREE.Vector3(x, 0, z);
}

function updateAvatarMovement() {
    if (!avatar) return;

    if (!isMoving) {
        waitTime++;
        if (waitTime >= maxWaitTime) {
            // Choisir une nouvelle destination
            targetPosition = getRandomPosition();
            isMoving = true;
            waitTime = 0;
            
            // Orienter l'avatar vers la cible
            const direction = new THREE.Vector3()
                .subVectors(targetPosition, currentPosition)
                .normalize();
            const angle = Math.atan2(direction.x, direction.z);
            avatar.rotation.y = angle;
        }
    } else {
        // Se déplacer vers la cible
        const direction = new THREE.Vector3()
            .subVectors(targetPosition, currentPosition);
        const distance = direction.length();

        if (distance < 0.1) {
            // Arrivé à destination
            isMoving = false;
            currentPosition.copy(targetPosition);
            avatar.position.set(currentPosition.x, 0, currentPosition.z);
        } else {
            // Continuer à avancer
            direction.normalize().multiplyScalar(moveSpeed);
            currentPosition.add(direction);
            avatar.position.set(currentPosition.x, 0, currentPosition.z);

            // Petite animation de marche (bobbing)
            const bobbing = Math.sin(Date.now() * 0.01) * 0.05;
            avatar.position.y = bobbing;
        }
    }
}

function animate() {
    animationId = requestAnimationFrame(animate);

    // Mettre à jour le mouvement de l'avatar
    updateAvatarMovement();

    renderer.render(scene, camera);
}
</script>

<template>
    <div class="avatar-3d-viewer">
        <canvas ref="canvasRef" :width="width" :height="height"></canvas>
    </div>
</template>

<style scoped>
.avatar-3d-viewer {
    display: inline-block;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

canvas {
    display: block;
}
</style>

