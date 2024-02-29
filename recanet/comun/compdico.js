// Comprueba si el c�digo cuenta cliente (CCC) es correcto,
// es decir, que se cumple la autenticidad dada por el 
// d�gito de control.
// La funci�n es l�gica, devolviendo verdadero si el n�mero
// es correcto y falso en caso contrario

// Para el c�lculo de cada uno de los dos d�gitos de control
// se emplea el m�dulo 11. Por consiguiente, la suma de los 
// productos obtenidos de multiplicar las cifras de las 
// informaciones a verificar por sus correspondientes pesos
// se divide entre 11. La diferencia del resto obtenido de la
// divisi�n y 11 ser� el d�gito de control correspondiente. 
// Dado que este control est� computado por una sola cifra, si
// el d�gito de control resultante fuera 10, se aplicar� en 
// su lugar el d�gito 1, y si fuera 11 el 0.
// Los pesos utilizados son los mismos para el c�lculo de los
// dos d�gitos y son comenzando por las unidades 6,3,7,9,10,5,
// 8,4,2 y 1) si bien los 3 �ltimos no tienen utilidad para 
// calcular el d�gito entidad oficina.

function compdico (cccx) {
 
  // Si la ristra no tiene 20 caracteres
  if (cccx.length!=20) return false; 
  pesos = new Array (6,3,7,9,10,5,8,4,2,1);

  // Entidad - oficina
  entiofic = cccx.substring (0,8);
  // Caracteres de control
  caracont = cccx.substring (8,10);
  // Cuenta
  cuen = cccx.substring (10,cccx.length);

  car1 = 0;
  car2 = 0;  // Caracteres de ctrol a calcular

  tam = entiofic.length-1;
  for (i=tam; i>=0; i--) {
    car1 += (parseInt (entiofic.charAt(i)) * pesos[tam-i]);
  }

  tam = cuen.length - 1;
  for (i=tam; i>=0; i--) {
    car2 = car2 + (parseInt (cuen.charAt(i)) * pesos[tam-i]);
  }
  
  //alert (car1+"\n"+car2);
  car1 = 11 - (car1 % 11);
  car2 = 11 - (car2 % 11);

  if (car1 == 11) { car1 = 0; }
  if (car1 == 10) { car1 = 1; }
 
  if (car2 == 11) { car2 = 0; }
  if (car2 == 10) { car2 = 1; }

  if ((""+car1+""+car2) != caracont) return false;
  else return true; 
}
