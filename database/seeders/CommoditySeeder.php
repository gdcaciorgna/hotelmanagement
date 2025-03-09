<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommoditySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commoditiesInfo = [
            'Pileta climatizada' => 'Pileta techada diseñada y equipada con las instalaciones necesarias para disfrutarla todo el año, aunque el clima no sea el más adecuado.',
            'Quincho de reuniones' => 'Disfruta de nuestro acogedor quincho de reuniones en el hotel, ideal para encuentros sociales y momentos relajados. Equipado con todo lo necesario para una experiencia confortable, es el lugar perfecto para compartir momentos memorables con amigos, familiares o colegas.',
            'Gimnasio' => 'Descubre nuestro gimnasio exclusivo en el hotel, equipado con lo último en tecnología fitness para que cada entrenamiento sea una experiencia revitalizante. Desde entrenamientos personalizados hasta clases grupales dinámicas.',
            'Sala de juegos' => 'Sumérgete en la diversión en nuestra sala de juegos, un espacio diseñado para el entretenimiento de todas las edades. Disfruta de una amplia variedad de juegos de mesa, arcade, metegol y billar, ideal para compartir momentos inolvidables con amigos y familia en un ambiente relajado y acogedor.',
            'Spa y centro de bienestar' => 'Relájate y rejuvenece en nuestro lujoso spa, donde ofrecemos una variedad de tratamientos de bienestar, masajes y servicios de belleza para una experiencia de relajación total.',
            'Club infantil' => 'Espacio diseñado especialmente para niños, con actividades supervisadas, juegos educativos y entretenimiento para que los más pequeños disfruten al máximo.',
            'Restaurante gourmet' => 'Disfruta de una experiencia culinaria excepcional en nuestro restaurante gourmet, donde nuestro chef ejecutivo prepara platos creativos con ingredientes frescos y de temporada.',
            'Actividades acuáticas' => 'Disfruta de una variedad de actividades acuáticas, como buceo, snorkel, y paseos en kayak organizados por nuestro equipo de recreación.',
            'Canchas deportivas' => 'Canchas de tenis, pádel o deportes similares disponibles para el uso de nuestros huéspedes, con alquiler de equipos y clases disponibles bajo solicitud.',
            'Club nocturno' => 'Disfruta de la vida nocturna en nuestro exclusivo club nocturno, con música en vivo, DJs y ambiente vibrante para una experiencia nocturna inolvidable.'
        ];
        
        foreach ($commoditiesInfo as $title => $value) {
            DB::table('commodities')->insert([
                'title' => $title,
                'description' => $value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
