<?php

// Patrones
$patronAnio = "/^(18|19|20)\d{2}$/"; // Año entre 1800 y 2099 
$patronNombreYApellidos = "/^([A-ZÁÉÍÓÚÑ][a-záéíóúüñ]+(\s[A-ZÁÉÍÓÚÑ][a-záéíóúüñ]+){0,3})$/"; // Hasta 3 palabras con mayúscula inicial y letras con acentos
$patronEmail = "/^[A-Za-zÁÉÍÓÚÄËÏÖÜ0-9._%+-]+@[A-Za-z]+\.(com|es)$/"; // Email válido con caracteres especiales opcionales y extensión .com o .es
$patronUrl = "/^(https?:\/\/)?(www\.)?[a-zA-Z0-9.-]+\.(com|es)$/"; // URL que empieza opcionalmente con http:// o https://, incluye www opcional
$patronFecha = "/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(18|19|20)\d{2}$/"; // Fecha en formato dd/mm/yyyy con año entre 1800 y 2099
$patronDni = "/^\d{8}[A-Z]$/"; // DNI español con 8 dígitos y una letra mayúscula
$patronPais = "/^(España|Alemania|Portugal|Italia)$/"; // País restringido a 4 opciones específicas
$patronMatricula = "/^\d{4}[B-DF-HJ-NP-TV-Z]{3}$/"; // Matrícula española con 4 números y 3 letras (sin vocales)
$patronCodigoActor = "/^([A-Z][0-9]+)$/"; //Patron para el codigo del actor

