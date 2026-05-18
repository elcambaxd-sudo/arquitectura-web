CREATE DATABASE IF NOT EXISTS arquitectura_web
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE arquitectura_web;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS galeria_proyectos;
DROP TABLE IF EXISTS mensajes_contacto;
DROP TABLE IF EXISTS articulos;
DROP TABLE IF EXISTS equipo;
DROP TABLE IF EXISTS servicios;
DROP TABLE IF EXISTS proyectos;
DROP TABLE IF EXISTS categorias_proyecto;
DROP TABLE IF EXISTS configuracion_sitio;
DROP TABLE IF EXISTS administradores;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE administradores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol VARCHAR(60) NOT NULL DEFAULT 'admin',
  estado TINYINT(1) NOT NULL DEFAULT 1,
  fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categorias_proyecto (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  descripcion TEXT NULL,
  orden INT NOT NULL DEFAULT 0,
  estado TINYINT(1) NOT NULL DEFAULT 1,
  INDEX idx_categoria_estado_orden (estado, orden)
) ENGINE=InnoDB;

CREATE TABLE proyectos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(180) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  categoria_id INT UNSIGNED NOT NULL,
  ubicacion VARCHAR(180) NOT NULL,
  anio YEAR NOT NULL,
  area VARCHAR(80) NULL,
  cliente VARCHAR(160) NULL,
  estado_proyecto VARCHAR(100) NULL,
  descripcion_corta TEXT NOT NULL,
  descripcion_larga MEDIUMTEXT NOT NULL,
  enfoque_diseno MEDIUMTEXT NULL,
  sostenibilidad MEDIUMTEXT NULL,
  imagen_principal VARCHAR(255) NULL,
  destacado TINYINT(1) NOT NULL DEFAULT 0,
  publicado TINYINT(1) NOT NULL DEFAULT 1,
  orden INT NOT NULL DEFAULT 0,
  fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_proyectos_categoria
    FOREIGN KEY (categoria_id) REFERENCES categorias_proyecto(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  INDEX idx_proyectos_publicado_orden (publicado, orden),
  INDEX idx_proyectos_destacado (destacado),
  FULLTEXT INDEX ft_proyectos_busqueda (titulo, ubicacion, descripcion_corta)
) ENGINE=InnoDB;

CREATE TABLE galeria_proyectos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  proyecto_id INT UNSIGNED NOT NULL,
  imagen VARCHAR(255) NOT NULL,
  alt_text VARCHAR(255) NULL,
  orden INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_galeria_proyecto
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  INDEX idx_galeria_proyecto_orden (proyecto_id, orden)
) ENGINE=InnoDB;

CREATE TABLE servicios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(160) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  descripcion TEXT NOT NULL,
  beneficio_cliente TEXT NOT NULL,
  icono VARCHAR(80) NOT NULL DEFAULT 'square',
  orden INT NOT NULL DEFAULT 0,
  estado TINYINT(1) NOT NULL DEFAULT 1,
  INDEX idx_servicios_estado_orden (estado, orden)
) ENGINE=InnoDB;

CREATE TABLE equipo (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(140) NOT NULL,
  cargo VARCHAR(140) NOT NULL,
  especialidad VARCHAR(180) NOT NULL,
  biografia TEXT NOT NULL,
  foto VARCHAR(255) NULL,
  orden INT NOT NULL DEFAULT 0,
  estado TINYINT(1) NOT NULL DEFAULT 1,
  INDEX idx_equipo_estado_orden (estado, orden)
) ENGINE=InnoDB;

CREATE TABLE articulos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(180) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  categoria VARCHAR(120) NOT NULL,
  autor VARCHAR(140) NOT NULL,
  fecha_publicacion DATE NOT NULL,
  imagen_destacada VARCHAR(255) NULL,
  resumen TEXT NOT NULL,
  contenido MEDIUMTEXT NOT NULL,
  publicado TINYINT(1) NOT NULL DEFAULT 1,
  fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_articulos_publicado_fecha (publicado, fecha_publicacion),
  FULLTEXT INDEX ft_articulos_busqueda (titulo, resumen, contenido)
) ENGINE=InnoDB;

CREATE TABLE mensajes_contacto (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(140) NOT NULL,
  email VARCHAR(160) NOT NULL,
  telefono VARCHAR(60) NULL,
  tipo_proyecto VARCHAR(120) NOT NULL,
  presupuesto_estimado VARCHAR(120) NULL,
  mensaje TEXT NOT NULL,
  estado VARCHAR(40) NOT NULL DEFAULT 'nuevo',
  fecha_envio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_mensajes_estado_fecha (estado, fecha_envio)
) ENGINE=InnoDB;

CREATE TABLE configuracion_sitio (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(120) NOT NULL UNIQUE,
  valor TEXT NULL
) ENGINE=InnoDB;

INSERT INTO administradores (nombre, email, password_hash, rol, estado) VALUES
('Administrador Demo', 'admin@demo.com', '$2y$10$qyNlphlRyTj4diQM6RLB7./YZlUz7Kb6Wt.rnC9zIKPegabFSp8e.', 'admin', 1);

INSERT INTO categorias_proyecto (nombre, slug, descripcion, orden, estado) VALUES
('Residencial', 'residencial', 'Viviendas unifamiliares, multifamiliares y espacios de vida cotidiana.', 1, 1),
('Comercial', 'comercial', 'Locales, oficinas y espacios de atencion al publico.', 2, 1),
('Cultural', 'cultural', 'Equipamientos para encuentro, memoria y expresion colectiva.', 3, 1),
('Educacional', 'educacional', 'Espacios de aprendizaje, aulas y campus de escala variable.', 4, 1),
('Interiorismo', 'interiorismo', 'Diseño interior, atmosfera, materialidad y mobiliario.', 5, 1),
('Paisaje y ciudad', 'paisaje-y-ciudad', 'Paisaje, espacio publico y estrategias urbanas.', 6, 1),
('Equipamiento', 'equipamiento', 'Infraestructura social, productiva y de servicios.', 7, 1),
('Remodelacion', 'remodelacion', 'Actualizacion de edificios existentes y cambios de uso.', 8, 1),
('Producto', 'producto', 'Mobiliario, objetos y sistemas espaciales.', 9, 1),
('Espacio publico', 'espacio-publico', 'Plazas, parques, calles y soportes de convivencia.', 10, 1);

INSERT INTO proyectos
(titulo, slug, categoria_id, ubicacion, anio, area, cliente, estado_proyecto, descripcion_corta, descripcion_larga, enfoque_diseno, sostenibilidad, imagen_principal, destacado, publicado, orden)
VALUES
('Casa Ladera Clara', 'casa-ladera-clara', 1, 'Cieneguilla, Lima', 2025, '320 m2', 'Familia privada', 'Anteproyecto',
'Vivienda de patios escalonados que abre la vida domestica hacia el paisaje seco.',
'La casa se organiza como una secuencia de umbrales entre interior, patio y pendiente. Cada recinto busca una relacion precisa con luz, sombra y viento, evitando gestos innecesarios y priorizando una experiencia serena de habitar.',
'La propuesta trabaja con plataformas, muros de contencion habitables y vacios que regulan privacidad sin perder continuidad visual.',
'Se incorporan ventilacion cruzada, sombreados profundos, jardines de bajo consumo hidrico y materiales de mantenimiento sencillo.',
'projects/casa-luz/03.jpeg', 1, 1, 1),
('Nave Productiva Norte', 'nave-productiva-norte', 7, 'Los Olivos, Lima', 2026, '1,480 m2', 'Empresa local', 'Construido',
'Infraestructura productiva que ordena trabajo, almacenamiento y comercio de escala barrial.',
'El edificio propone una envolvente robusta de ladrillo y grandes vanos controlados. Su valor esta en permitir cambios de uso, accesos eficientes y una imagen urbana sobria para un entorno de alta actividad.',
'Se priorizo una estructura clara, recorridos legibles y frentes activos capaces de adaptarse a nuevos arrendatarios.',
'La estrategia reduce sistemas mecanicos mediante iluminacion natural, ventilacion cruzada y materiales resistentes al desgaste.',
'projects/naves-productivas/01.jpeg', 1, 1, 2),
('Comedor Jardin Interior', 'comedor-jardin-interior', 5, 'Arequipa, Peru', 2025, '320 m2', 'Marca gastronomica', 'Proyecto ejecutivo',
'Interior comercial de doble altura donde vegetacion, color y mobiliario construyen identidad.',
'El proyecto transforma una nave existente en una experiencia gastronomica memorable. La doble altura permite organizar flujos y visuales mientras el mural, las luminarias y el mobiliario generan una atmosfera calida y reconocible.',
'La propuesta integra diseño grafico, vegetacion interior y piezas modulares para una operacion eficiente.',
'Se especifican luminarias LED, superficies lavables, especies resistentes y mobiliario reparable.',
'projects/interior-mural/04.jpeg', 1, 1, 3),
('Aula Umbral', 'aula-umbral', 4, 'Callao, Peru', 2024, '640 m2', 'Institucion educativa', 'En desarrollo',
'Modulo educativo flexible para talleres tecnicos y aprendizaje colaborativo.',
'Aula Umbral acerca la enseñanza a procesos reales de fabricacion, ensayo y reunion. El proyecto permite abrir o cerrar franjas de trabajo segun clima, seguridad y escala del grupo.',
'El diseño propone aulas conectadas con patios duros y zonas de demostracion, evitando pasillos residuales.',
'La envolvente trabaja con sombra, ventilacion cruzada y piezas prefabricadas para reducir desperdicio.',
'projects/naves-productivas/02.jpeg', 0, 1, 4),
('Patio Agua Baja', 'patio-agua-baja', 6, 'Villa Maria del Triunfo, Lima', 2024, '2,100 m2', 'Municipalidad distrital', 'Concurso',
'Espacio urbano con sombra, superficies drenantes y mobiliario comunitario.',
'La intervencion convierte un vacio urbano en un sistema de permanencia, juego y feria vecinal. El agua de lluvia se conduce a franjas vegetadas y la sombra se produce con estructuras ligeras.',
'El proyecto parte de talleres con vecinos y define piezas de bajo costo para construccion por etapas.',
'Usa pavimentos permeables, especies nativas y mobiliario reparable con mano de obra local.',
'projects/casa-luz/05.jpeg', 0, 1, 5),
('Sistema Banco Trama', 'sistema-banco-trama', 9, 'Lima, Peru', 2023, 'Linea de producto', 'Desarrollo interno', 'Prototipo',
'Familia de bancas y mesas para espacios comerciales y educativos.',
'El sistema nace de la necesidad de tener mobiliario resistente, apilable y facil de reparar. Sus piezas reducen cortes, simplifican transporte y permiten configuraciones variables.',
'La geometria se ajusta a medidas comerciales de madera y acero para evitar desperdicio.',
'Puede fabricarse con madera certificada y acabados de bajo VOC.',
'projects/interior-mural/02.jpeg', 0, 1, 6);

INSERT INTO galeria_proyectos (proyecto_id, imagen, alt_text, orden) VALUES
(1, 'projects/casa-luz/03.jpeg', 'Volumen principal de Casa Ladera Clara', 1),
(1, 'projects/casa-luz/01.jpeg', 'Vista lateral de vivienda y patio', 2),
(1, 'projects/casa-luz/02.jpeg', 'Detalle de circulacion vertical', 3),
(2, 'projects/naves-productivas/01.jpeg', 'Fachada de Nave Productiva Norte', 1),
(2, 'projects/naves-productivas/02.jpeg', 'Patio de maniobras y fachada', 2),
(2, 'projects/naves-productivas/05.jpeg', 'Detalle de ladrillo y acceso', 3),
(3, 'projects/interior-mural/04.jpeg', 'Interior de comedor con mural', 1),
(3, 'projects/interior-mural/05.jpeg', 'Vista de doble altura', 2),
(3, 'projects/interior-mural/06.jpeg', 'Mobiliario y barra de atencion', 3),
(4, 'projects/naves-productivas/03.jpeg', 'Modulo educativo flexible', 1),
(5, 'projects/casa-luz/05.jpeg', 'Patio urbano y sombra', 1),
(6, 'projects/interior-mural/02.jpeg', 'Banco modular en contexto comercial', 1);

INSERT INTO servicios (titulo, slug, descripcion, beneficio_cliente, icono, orden, estado) VALUES
('Arquitectura', 'arquitectura', 'Diseño integral de viviendas, equipamientos y edificios de uso mixto.', 'El cliente recibe una propuesta clara, viable y documentada para construir con confianza.', 'architecture', 1, 1),
('Urbanismo y paisaje', 'urbanismo-y-paisaje', 'Planes maestros, espacio publico, paisaje operativo y estrategias urbanas.', 'Permite transformar lugares complejos en sistemas habitables, medibles y sostenibles.', 'landscape', 2, 1),
('Interiorismo', 'interiorismo', 'Diseño de atmosferas, materialidad, mobiliario y experiencia interior.', 'Optimiza uso, identidad y mantenimiento de espacios cotidianos o comerciales.', 'interior', 3, 1),
('Diseño comercial', 'diseno-comercial', 'Locales, restaurantes, oficinas y experiencias de marca en el espacio.', 'Convierte la operacion comercial en un espacio memorable y eficiente.', 'store', 4, 1),
('Gestion y supervision de proyectos', 'gestion-y-supervision', 'Coordinacion tecnica, cronograma, costos, obra y control de calidad.', 'Reduce riesgos, retrabajos y decisiones improvisadas durante la ejecucion.', 'check', 5, 1),
('Consultoria arquitectonica', 'consultoria-arquitectonica', 'Diagnosticos, factibilidad, lineamientos de diseño y revision de proyectos.', 'Ayuda a tomar decisiones tempranas con informacion clara y criterio tecnico.', 'consulting', 6, 1),
('Estudios inmobiliarios', 'estudios-inmobiliarios', 'Cabidas, analisis normativo, escenarios de inversion y potencial de lote.', 'Permite evaluar oportunidades antes de comprar, invertir o desarrollar.', 'real-estate', 7, 1),
('Diseño de producto y mobiliario', 'diseno-de-producto-y-mobiliario', 'Piezas, sistemas y mobiliario a medida para proyectos y marcas.', 'Agrega identidad, control ergonomico y coherencia material al proyecto.', 'product', 8, 1);

INSERT INTO equipo (nombre, cargo, especialidad, biografia, foto, orden, estado) VALUES
('Alex Rivera', 'Director creativo', 'Concepto y estrategia espacial', 'Arquitecto enfocado en traducir necesidades humanas en estructuras claras, sensibles y construibles.', 'projects/casa-luz/03.jpeg', 1, 1),
('Marina Solis', 'Arquitecta principal', 'Vivienda y equipamiento', 'Coordina diseño arquitectonico, normativa y documentacion tecnica con atencion al detalle.', 'projects/naves-productivas/05.jpeg', 2, 1),
('Tomas Vidal', 'Jefe de proyectos', 'Gestion tecnica', 'Integra costos, cronogramas y especialidades para proteger la coherencia del proyecto.', 'projects/naves-productivas/01.jpeg', 3, 1),
('Clara Medina', 'Urbanista', 'Paisaje y ciudad', 'Trabaja con movilidad, espacio publico y sistemas verdes desde una mirada territorial.', 'projects/casa-luz/05.jpeg', 4, 1),
('Ines Pardo', 'Diseñadora de interiores', 'Materialidad y experiencia', 'Desarrolla atmosferas interiores, mobiliario y especificaciones de acabados.', 'projects/interior-mural/04.jpeg', 5, 1),
('Bruno Casta', 'Visualizador 3D', 'Imagen y narrativa visual', 'Produce imagenes, recorridos y piezas visuales que comunican decisiones de diseño.', 'projects/interior-mural/06.jpeg', 6, 1),
('Lucia Arce', 'Coordinadora de obra', 'Supervision y calidad', 'Acompaña procesos en campo para resolver interferencias y cuidar el resultado construido.', 'projects/naves-productivas/02.jpeg', 7, 1);

INSERT INTO articulos (titulo, slug, categoria, autor, fecha_publicacion, imagen_destacada, resumen, contenido, publicado) VALUES
('Habitar con menos ruido', 'habitar-con-menos-ruido', 'Arquitectura', 'Equipo editorial', '2026-04-12', 'projects/casa-luz/03.jpeg', 'Una reflexion sobre espacios serenos, utiles y atentos al paso del tiempo.', 'Diseñar con menos ruido no significa hacer menos arquitectura. Significa retirar lo innecesario para que luz, proporcion, uso y materia puedan hablar con claridad. Un espacio sobrio permite que la vida cotidiana encuentre su propio ritmo.', 1),
('La ciudad empieza en la sombra', 'la-ciudad-empieza-en-la-sombra', 'Ciudad', 'Clara Medina', '2026-03-20', 'projects/casa-luz/05.jpeg', 'El confort urbano puede empezar con decisiones simples: sombra, agua y descanso.', 'La sombra es infraestructura. En ciudades expuestas al calor, un arbol, una cubierta o un portal pueden cambiar la forma en que caminamos, esperamos y nos encontramos. Pensar la ciudad desde la sombra es pensarla desde el cuerpo.', 1),
('Interiores que trabajan mejor', 'interiores-que-trabajan-mejor', 'Interiorismo', 'Ines Pardo', '2026-02-08', 'projects/interior-mural/04.jpeg', 'Interiorismo comercial como herramienta de operacion, identidad y cuidado.', 'Un interior exitoso no solo se mira bien. Circula bien, se limpia bien, se repara bien y ayuda a que las personas entiendan donde estan. La belleza aparece cuando operacion e identidad dejan de competir.', 1);

INSERT INTO configuracion_sitio (clave, valor) VALUES
('site_name', 'Estudio Raiz'),
('site_tagline', 'Diseñamos espacios conscientes para vivir, trabajar y encontrarnos mejor.'),
('site_description', 'Estudio de arquitectura, urbanismo e interiorismo con enfoque sostenible y humano.'),
('contact_email', 'contacto@estudioraiz.test'),
('contact_phone', '+51 999 000 000'),
('contact_address', 'Av. Central 245, Miraflores, Lima'),
('hero_title', 'Arquitectura que escucha el lugar'),
('hero_text', 'Diseñamos espacios claros, sensibles y funcionales para personas, ciudad y entorno.');
